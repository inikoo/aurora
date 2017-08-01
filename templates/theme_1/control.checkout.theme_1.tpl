
<span class="success very_discreet " style="margin-left:20px;margin-right:20px;font-style: italic">@{t}Success{/t}:</span>

<span id="order_confirmation" class="button  {if !($content.send_email==1 and $metadata.emails.order_notification.published_key>0 and $metadata.emails.order_notification.key>0)}very_discreet{/if}">
    <span    onclick="change_view(state.request + '&subtab=webpage.email_template')"><i class="fa fa-envelope-o discreet"   aria-hidden="true"></i> {t}Order confirmation email{/t}</span> <i id="send_email"  class="fa fa-check {if $content.send_email==1 and $metadata.emails.order_notification.published_key>0 and $metadata.emails.order_notification.key>0}success{/if}" aria-hidden="true"></i>
    </span>
