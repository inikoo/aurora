//@line 38 "/home/work/src/browser/components/privatebrowsing/src/nsPrivateBrowsingService.js"

Components.utils.import("resource://gre/modules/XPCOMUtils.jsm");

////////////////////////////////////////////////////////////////////////////////
//// Utilities

/**
 * Returns true if the string passed in is part of the root domain of the
 * current string.  For example, if this is "www.mozilla.org", and we pass in
 * "mozilla.org", this will return true.  It would return false the other way
 * around.
 */
String.prototype.hasRootDomain = function hasRootDomain(aDomain)
{
  let index = this.indexOf(aDomain);
  // If aDomain is not found, we know we do not have it as a root domain.
  if (index == -1)
    return false;

  // If the strings are the same, we obviously have a match.
  if (this == aDomain)
    return true;

  // Otherwise, we have aDomain as our root domain iff the index of aDomain is
  // aDomain.length subtracted from our length and (since we do not have an
  // exact match) the character before the index is a dot or slash.
  let prevChar = this[index - 1];
  return (index == (this.length - aDomain.length)) &&
         (prevChar == "." || prevChar == "/");
}

////////////////////////////////////////////////////////////////////////////////
//// Constants

const Cc = Components.classes;
const Ci = Components.interfaces;
const Cu = Components.utils;
const Cr = Components.results;

////////////////////////////////////////////////////////////////////////////////
//// PrivateBrowsingService

function PrivateBrowsingService() {
  this._obs.addObserver(this, "profile-after-change", true);
  this._obs.addObserver(this, "quit-application-granted", true);
  this._obs.addObserver(this, "private-browsing", true);
}

PrivateBrowsingService.prototype = {
  // Observer Service
  __obs: null,
  get _obs() {
    if (!this.__obs)
      this.__obs = Cc["@mozilla.org/observer-service;1"].
                   getService(Ci.nsIObserverService);
    return this.__obs;
  },

  // Preferences Service
  __prefs: null,
  get _prefs() {
    if (!this.__prefs)
      this.__prefs = Cc["@mozilla.org/preferences-service;1"].
                     getService(Ci.nsIPrefBranch);
    return this.__prefs;
  },

  // Whether the private browsing mode is currently active or not.
  _inPrivateBrowsing: false,

  // Saved browser state before entering the private mode.
  _savedBrowserState: null,

  // Whether we're in the process of shutting down
  _quitting: false,

  // How to treat the non-private session
  _saveSession: true,

  // Make sure we don't allow re-enterant changing of the private mode
  _alreadyChangingMode: false,

  // Whether we're entering the private browsing mode at application startup
  _autoStart: false,

  // Whether the private browsing mode has been started automatically
  _autoStarted: false,

  // XPCOM registration
  classDescription: "PrivateBrowsing Service",
  contractID: "@mozilla.org/privatebrowsing;1",
  classID: Components.ID("{c31f4883-839b-45f6-82ad-a6a9bc5ad599}"),
  _xpcom_categories: [
    { category: "app-startup", service: true }
  ],

  QueryInterface: XPCOMUtils.generateQI([Ci.nsIPrivateBrowsingService, 
                                         Ci.nsIObserver,
                                         Ci.nsISupportsWeakReference]),

  _unload: function PBS__destroy() {
    // Force an exit from the private browsing mode on shutdown
    this._quitting = true;
    if (this._inPrivateBrowsing)
      this.privateBrowsingEnabled = false;
  },

  _onBeforePrivateBrowsingModeChange: function PBS__onBeforePrivateBrowsingModeChange() {
    // nothing needs to be done here if we're auto-starting
    if (!this._autoStart) {
      let ss = Cc["@mozilla.org/browser/sessionstore;1"].
               getService(Ci.nsISessionStore);
      let blankState = JSON.stringify({
        "windows": [{
          "tabs": [{
            "entries": [{
              "url": "about:blank"
            }]
          }],
          "_closedTabs": []
        }]
      });

      // whether we should save and close the current session
      this._saveSession = true;
      try {
        if (this._prefs.getBoolPref("browser.privatebrowsing.keep_current_session"))
          this._saveSession = false;
      } catch (ex) {}

      if (this._inPrivateBrowsing) {
        // save the whole browser state in order to restore all windows/tabs later
        if (this._saveSession && !this._savedBrowserState) {
          if (this._getBrowserWindow())
            this._savedBrowserState = ss.getBrowserState();
          else // no open browser windows, just restore a blank state on exit
            this._savedBrowserState = blankState;
        }
      }
      if (!this._quitting && this._saveSession) {
        let browserWindow = this._getBrowserWindow();

        // if there are open browser windows, load a dummy session to get a distinct 
        // separation between private and non-private sessions
        if (browserWindow) {
          // set an empty session to transition from/to pb mode, see bug 476463
          ss.setBrowserState(blankState);

          // just in case the only remaining window after setBrowserState is different.
          // it probably shouldn't be with the current sessionstore impl, but we shouldn't
          // rely on behaviour the API doesn't guarantee
          let browser = this._getBrowserWindow().gBrowser;

          // this ensures a clean slate from which to transition into or out of
          // private browsing
          browser.addTab();
          browser.removeTab(browser.tabContainer.firstChild);
        }
      }
    }
    else
      this._saveSession = false;
  },

  _onAfterPrivateBrowsingModeChange: function PBS__onAfterPrivateBrowsingModeChange() {
    // nothing to do here if we're auto-starting or the current session is being
    // used
    if (!this._autoStart && this._saveSession) {
      let ss = Cc["@mozilla.org/browser/sessionstore;1"].
               getService(Ci.nsISessionStore);
      // if we have transitioned out of private browsing mode and the session is
      // to be restored, do it now
      if (!this._inPrivateBrowsing) {
        ss.setBrowserState(this._savedBrowserState);
        this._savedBrowserState = null;
      }
      else {
        // otherwise, if we have transitioned into private browsing mode, load
        // about:privatebrowsing
        let privateBrowsingState = {
          "windows": [{
            "tabs": [{
              "entries": [{
                "url": "about:privatebrowsing"
              }]
            }],
            "_closedTabs": []
          }]
        };
        // Transition into private browsing mode
        ss.setBrowserState(JSON.stringify(privateBrowsingState));
      }
    }
  },

  _canEnterPrivateBrowsingMode: function PBS__canEnterPrivateBrowsingMode() {
    let cancelEnter = Cc["@mozilla.org/supports-PRBool;1"].
                      createInstance(Ci.nsISupportsPRBool);
    cancelEnter.data = false;
    this._obs.notifyObservers(cancelEnter, "private-browsing-cancel-vote", "enter");
    return !cancelEnter.data;
  },

  _canLeavePrivateBrowsingMode: function PBS__canLeavePrivateBrowsingMode() {
    let cancelLeave = Cc["@mozilla.org/supports-PRBool;1"].
                      createInstance(Ci.nsISupportsPRBool);
    cancelLeave.data = false;
    this._obs.notifyObservers(cancelLeave, "private-browsing-cancel-vote", "exit");
    return !cancelLeave.data;
  },

  _getBrowserWindow: function PBS__getBrowserWindow() {
    return Cc["@mozilla.org/appshell/window-mediator;1"].
           getService(Ci.nsIWindowMediator).
           getMostRecentWindow("navigator:browser");
  },

  // nsIObserver

  observe: function PBS_observe(aSubject, aTopic, aData) {
    switch (aTopic) {
      case "profile-after-change":
        // If the autostart prefs has been set, simulate entering the
        // private browsing mode upon startup.
        // This won't interfere with the session store component, because
        // that component will be initialized on final-ui-startup.
        this._autoStart = this._prefs.getBoolPref("browser.privatebrowsing.autostart");
        if (this._autoStart) {
          this._autoStarted = true;
          this.privateBrowsingEnabled = true;
          this._autoStart = false;
        }
        this._obs.removeObserver(this, "profile-after-change");
        break;
      case "quit-application-granted":
        this._unload();
        break;
      case "private-browsing":
        // clear all auth tokens
        let sdr = Cc["@mozilla.org/security/sdr;1"].
                  getService(Ci.nsISecretDecoderRing);
        sdr.logoutAndTeardown();
    
        // clear plain HTTP auth sessions
        let authMgr = Cc['@mozilla.org/network/http-auth-manager;1'].
                      getService(Ci.nsIHttpAuthManager);
        authMgr.clearAll();

        // Prevent any SSL sockets from remaining open.  Without this, SSL
        // websites may fail to load after switching the private browsing mode
        // because the SSL sockets may still be open while the corresponding
        // NSS resources have been destroyed by the logoutAndTeardown call
        // above.  See bug 463256 for more information.
        let ios = Cc["@mozilla.org/network/io-service;1"].
                  getService(Ci.nsIIOService);
        if (!ios.offline) {
          ios.offline = true;
          ios.offline = false;
        }

        if (!this._inPrivateBrowsing) {
          // Clear the error console
          let consoleService = Cc["@mozilla.org/consoleservice;1"].
                               getService(Ci.nsIConsoleService);
          consoleService.logStringMessage(null); // trigger the listeners
          consoleService.reset();
        }
        break;
    }
  },

  // nsIPrivateBrowsingService

  /**
   * Return the current status of private browsing.
   */
  get privateBrowsingEnabled PBS_get_privateBrowsingEnabled() {
    return this._inPrivateBrowsing;
  },

  /**
   * Enter or leave private browsing mode.
   */
  set privateBrowsingEnabled PBS_set_privateBrowsingEnabled(val) {
    // Allowing observers to set the private browsing status from their
    // notification handlers is not desired, because it will change the
    // status of the service while it's in the process of another transition.
    // So, we detect a reentrant call here and throw an error.
    // This is documented in nsIPrivateBrowsingService.idl.
    if (this._alreadyChangingMode)
      throw Cr.NS_ERROR_FAILURE;

    try {
      this._alreadyChangingMode = true;

      if (val != this._inPrivateBrowsing) {
        if (val) {
          if (!this._canEnterPrivateBrowsingMode())
            return;
        }
        else {
          if (!this._canLeavePrivateBrowsingMode())
            return;
        }

        this._autoStarted = val ?
          this._prefs.getBoolPref("browser.privatebrowsing.autostart") : false;
        this._inPrivateBrowsing = val != false;

        let data = val ? "enter" : "exit";

        let quitting = Cc["@mozilla.org/supports-PRBool;1"].
                       createInstance(Ci.nsISupportsPRBool);
        quitting.data = this._quitting;

        // notify observers of the pending private browsing mode change
        this._obs.notifyObservers(quitting, "private-browsing-change-granted", data);

        // destroy the current session and start initial cleanup
        this._onBeforePrivateBrowsingModeChange();

        this._obs.notifyObservers(quitting, "private-browsing", data);

        // load the appropriate session
        this._onAfterPrivateBrowsingModeChange();
      }
    } catch (ex) {
      Cu.reportError("Exception thrown while processing the " +
        "private browsing mode change request: " + ex.toString());
    } finally {
      this._alreadyChangingMode = false;
    }
  },

  /**
   * Whether private browsing has been started automatically.
   */
  get autoStarted PBS_get_autoStarted() {
    return this._autoStarted;
  },

  removeDataFromDomain: function PBS_removeDataFromDomain(aDomain)
  {
    // History
    let (bh = Cc["@mozilla.org/browser/global-history;2"].
              getService(Ci.nsIBrowserHistory)) {
      bh.removePagesFromHost(aDomain, true);
    }

    // Cache
    let (cs = Cc["@mozilla.org/network/cache-service;1"].
              getService(Ci.nsICacheService)) {
      // NOTE: there is no way to clear just that domain, so we clear out
      //       everything)
      try {
        cs.evictEntries(Ci.nsICache.STORE_ANYWHERE);
      } catch (ex) {
        Cu.reportError("Exception thrown while clearing the cache: " +
          ex.toString());
      }
    }

    // Cookies
    let (cm = Cc["@mozilla.org/cookiemanager;1"].
              getService(Ci.nsICookieManager)) {
      let enumerator = cm.enumerator;
      while (enumerator.hasMoreElements()) {
        let cookie = enumerator.getNext().QueryInterface(Ci.nsICookie);
        if (cookie.host.hasRootDomain(aDomain))
          cm.remove(cookie.host, cookie.name, cookie.path, false);
      }
    }

    // Downloads
    let (dm = Cc["@mozilla.org/download-manager;1"].
              getService(Ci.nsIDownloadManager)) {
      // Active downloads
      let enumerator = dm.activeDownloads;
      while (enumerator.hasMoreElements()) {
        let dl = enumerator.getNext().QueryInterface(Ci.nsIDownload);
        if (dl.source.host.hasRootDomain(aDomain)) {
          dm.cancelDownload(dl.id);
          dm.removeDownload(dl.id);
        }
      }

      // Completed downloads
      let db = dm.DBConnection;
      // NOTE: This is lossy, but we feel that it is OK to be lossy here and not
      //       invoke the cost of creating a URI for each download entry and
      //       ensure that the hostname matches.
      let stmt = db.createStatement(
        "DELETE FROM moz_downloads " +
        "WHERE source LIKE ?1 ESCAPE '/' " +
        "AND state NOT IN (?2, ?3, ?4)"
      );
      let pattern = stmt.escapeStringForLIKE(aDomain, "/");
      stmt.bindStringParameter(0, "%" + pattern + "%");
      stmt.bindInt32Parameter(1, Ci.nsIDownloadManager.DOWNLOAD_DOWNLOADING);
      stmt.bindInt32Parameter(2, Ci.nsIDownloadManager.DOWNLOAD_PAUSED);
      stmt.bindInt32Parameter(3, Ci.nsIDownloadManager.DOWNLOAD_QUEUED);
      try {
        stmt.execute();
      }
      finally {
        stmt.finalize();
      }

      // We want to rebuild the list if the UI is showing, so dispatch the
      // observer topic
      let os = Cc["@mozilla.org/observer-service;1"].
               getService(Ci.nsIObserverService);
      os.notifyObservers(null, "download-manager-remove-download", null);
    }

    // Passwords
    let (lm = Cc["@mozilla.org/login-manager;1"].
              getService(Ci.nsILoginManager)) {
      // Clear all passwords for domain
      try {
        let logins = lm.getAllLogins({});
        for (let i = 0; i < logins.length; i++)
          if (logins[i].hostname.hasRootDomain(aDomain))
            lm.removeLogin(logins[i]);
      }
      // XXXehsan: is there a better way to do this rather than this
      // hacky comparison?
      catch (ex if ex.message.indexOf("User canceled Master Password entry") != -1) { }

      // Clear any "do not save for this site" for this domain
      let disabledHosts = lm.getAllDisabledHosts({});
      for (let i = 0; i < disabledHosts.length; i++)
        if (disabledHosts[i].hasRootDomain(aDomain))
          lm.setLoginSavingEnabled(disabledHosts, true);
    }

    // Permissions
    let (pm = Cc["@mozilla.org/permissionmanager;1"].
              getService(Ci.nsIPermissionManager)) {
      // Enumerate all of the permissions, and if one matches, remove it
      let enumerator = pm.enumerator;
      while (enumerator.hasMoreElements()) {
        let perm = enumerator.getNext().QueryInterface(Ci.nsIPermission);
        if (perm.host.hasRootDomain(aDomain))
          pm.remove(perm.host, perm.type);
      }
    }

    // Content Preferences
    let (cp = Cc["@mozilla.org/content-pref/service;1"].
              getService(Ci.nsIContentPrefService)) {
      let db = cp.DBConnection;
      // First we need to get the list of "groups" which are really just domains
      let names = [];
      let stmt = db.createStatement(
        "SELECT name " +
        "FROM groups " +
        "WHERE name LIKE ?1 ESCAPE '/'"
      );
      let pattern = stmt.escapeStringForLIKE(aDomain, "/");
      stmt.bindStringParameter(0, "%" + pattern);
      try {
        while (stmt.executeStep())
          if (stmt.getString(0).hasRootDomain(aDomain))
            names.push(stmt.getString(0));
      }
      finally {
        stmt.finalize();
      }

      // Now, for each name we got back, remove all of its prefs.
      for (let i = 0; i < names.length; i++) {
        // The service only cares about the host of the URI, so we don't need a
        // full nsIURI object here.
        let uri = { host: names[i]};
        let enumerator = cp.getPrefs(uri).enumerator;
        while (enumerator.hasMoreElements()) {
          let pref = enumerator.getNext().QueryInterface(Ci.nsIProperty);
          cp.removePref(uri, pref.name);
        }
      }
    }

    // Everybody else (including extensions)
    this._obs.notifyObservers(null, "browser:purge-domain-data", aDomain);
  }
};

function NSGetModule(compMgr, fileSpec)
  XPCOMUtils.generateModule([PrivateBrowsingService]);
