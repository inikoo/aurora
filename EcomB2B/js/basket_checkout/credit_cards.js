function use_this_credit_card(element){

    $('.credit_cards_row').addClass('hide');
    var card_info=$(element).closest('.credit_cards_row').find('.card_info');


    let cc_row=$('.credit_card_input_row')
    cc_row.removeClass('hide')


    cc_row.find('.card_info').html(card_info.html()).data('token',card_info.data('token')).data('nonce',card_info.data('nonce'));

    $('#BTree_saved_credit_cards_form .show_saved_cards_list').removeClass('hide')


}

function use_other_credit_card(){

    $('#BTree_saved_credit_cards_form').addClass('hide');
    $('#BTree_credit_card_form').removeClass('hide')

}

function show_saved_cards(){

    $('#BTree_saved_credit_cards_form').removeClass('hide');
    $('#BTree_credit_card_form').addClass('hide');

    $('#BTree_saved_credit_cards_form .show_saved_cards_list').addClass('hide');

    $('.credit_cards_row').removeClass('hide');
    $('.credit_card_input_row').addClass('hide')


}