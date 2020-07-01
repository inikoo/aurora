<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3:24 pmWednesday, 1 July 2020 (MYT) Time in Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0
*/

class google_drive {

    public function __construct($account,$token_filename='keyring/goggle_drive.token.json') {


        $this->client  = $this->get_client($token_filename);
        $this->service = new Google_Service_Drive($this->client);

        if ($account->properties('google_drive_folder_key')) {

            try {
                $this->service->files->get($account->properties('google_drive_folder_key'));
                $this->aurora_folder_key = $account->properties('google_drive_folder_key');

                return;
            } catch (Exception $e) {
                $account_folder_key = $this->create_folder('aurora', '', ['au_location' => 'root']);
                $this->aurora_folder_key=$account_folder_key;
                $account->fast_update_json_field('Account Properties', 'google_drive_folder_key', $this->aurora_folder_key, 'Account Data');


            }

        } else {

            $account_folder_key = $this->find_file('', 'aurora', 'folder', ['au_location' => 'root']);
            if (!$account_folder_key) {
                $account_folder_key = $this->create_folder('aurora', '', ['au_location' => 'root']);
            }

            $this->aurora_folder_key=$account_folder_key;
            $account->fast_update_json_field('Account Properties', 'google_drive_folder_key', $this->aurora_folder_key, 'Account Data');


        }


    }
    function get_client($tokenPath) {
        $client = new Google_Client();
        $client->setApplicationName('Aurora google drive manager');
        $client->setScopes(
            [
                Google_Service_Drive::DRIVE_METADATA_READONLY,
                Google_Service_Drive::DRIVE_FILE
            ]
        );

        $client->setAuthConfig('keyring/google_drive.credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    public function set_store_folder($store) {


        if ($store->properties('google_drive_folder_key')) {

            try {
                $this->service->files->get($store->properties('google_drive_folder_key'));

                return;
            } catch (Exception $e) {

            }

        }

        $store_folder_key = $this->find_file(
            $this->aurora_folder_key, '', 'folder', [
                                        'au_location'  => 'store',
                                        'au_store_key' => $store->id
                                    ]
        );
        if (!$store_folder_key) {

            $store_folder_key = $this->create_folder(
                $store->get('Code'), $this->aurora_folder_key, [
                                       'au_location'  => 'store',
                                       'au_store_key' => $store->id
                                   ]
            );


            $store->fast_update_json_field('Store Properties', 'google_drive_folder_key', $store_folder_key);

        }

        $store_invoice_folder_key = $this->find_file(
            $store_folder_key, 'invoices', 'folder', [
                                 'au_location'  => 'invoices',
                                 'au_store_key' => $store->id
                             ]
        );
        if (!$store_invoice_folder_key) {
            $store_invoice_folder_key = $this->create_folder(
                'invoices', $store_folder_key, [
                              'au_location'  => 'invoices',
                              'au_store_key' => $store->id
                          ]
            );
            $store->fast_update_json_field('Store Properties', 'google_drive_folder_invoices_key', $store_invoice_folder_key);

        }


    }


    function create_root($account) {

        $file_id                 = $this->create_folder('aurora', '', ['au_location' => 'root']);
        $this->aurora_folder_key = $file_id;
        $account->fast_update_json_field('Account Properties', 'google_drive_folder_key', $this->aurora_folder_key, 'Account Data');

    }



    function create_folder($name, $parent_key, $app_properties) {


        $folder_data = array(
            'name'          => $name,
            'mimeType'      => 'application/vnd.google-apps.folder',
            'appProperties' => $app_properties
        );

        if ($parent_key != '') {
            $folder_data['parents'] = [$parent_key];
        }


        $fileMetadata = new Google_Service_Drive_DriveFile($folder_data);
        $file         = $this->service->files->create(
            $fileMetadata, array(
                             'fields' => 'id'
                         )
        );

        return $file->id;
    }

    function find_file($parent_folder_key, $name, $type, $metadata) {

        $search = 'trashed=false  ';
        foreach ($metadata as $key => $value) {
            $search .= " and  appProperties has { key='$key' and value='$value' }";
        }

        if ($parent_folder_key != '') {
            $search .= " and parents in '$parent_folder_key'";
        }

        if ($type == 'folder') {
            $search .= " and mimeType = 'application/vnd.google-apps.folder' ";
        }

        if ($name != '') {
            $search .= " and name = '$name' ";
        }


        $optParams = array(
            'pageSize' => 1,
            'fields'   => 'nextPageToken, files(id, name)',
            'q'        => $search
        );
        $results   = @$this->service->files->listFiles($optParams);

        foreach ($results->getFiles() as $file) {
            return $file->getId();
        }


        return false;


    }

    function upload($base_folder_key, $path, $metadata, $content) {


        $name = array_pop($path);

        $parent_folder_key = $base_folder_key;
        $path_index        = 0;
        foreach ($path as $node) {
            $_parent_folder_key = $parent_folder_key;
            if (!$parent_folder_key = $this->find_file($_parent_folder_key, $node, 'folder', $metadata[$path_index])) {
                $parent_folder_key = $this->create_folder($node, $_parent_folder_key, $metadata[$path_index]);

            }


            $path_index++;
        }

        if (!$file_key = $this->find_file($parent_folder_key, $name, 'file', $metadata[$path_index])) {


            $fileMetadata = new Google_Service_Drive_DriveFile(
                array(
                    'name'          => $name,
                    'parents'       => array($parent_folder_key),
                    'appProperties' => $metadata[$path_index]
                )
            );
            $file         = $this->service->files->create(
                $fileMetadata, array(
                                 'data'       => $content,
                                 'uploadType' => 'multipart',
                                 'fields'     => 'id'
                             )
            );

            // Returning file id of newly uploaded file
            $file_key = $file->id;

        }

        return $file_key;
    }

}
