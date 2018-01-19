var reload = false;
var paged = 2;
var loading = false;
function displayBarNotification(n,c,m){
    var nNote = jQuery("#nNote");
    if(n){
        nNote.attr('class', '').addClass("nNote " + c).fadeIn().html(m);
        setTimeout(function(){
            nNote.attr('class', '').hide("slow").html("");
        }, 10000);
    }else{
        nNote.attr('class', '').hide("slow").html("");
    }
}
function displayAjaxLoading(n){
    n?jQuery(".ajax-loading-block-window").show():jQuery(".ajax-loading-block-window").hide("slow");
}
function ShowPoupEditOrder(){
    displayAjaxLoading(true);
    jQuery.get(ajaxurl, {
        'action':'loadCartEditOrder'
    }, function(data) {
        jQuery.colorbox({
            html:data, 
            overlayClose: false,
            onClosed:function(){
                if(reload){
                    window.location.reload();
                }
            }
        });
        displayAjaxLoading(false);
    });
}
function ShowPoupOrderDetail(html){
    displayAjaxLoading(true);
    jQuery.colorbox({
        width: 840,
        html:html,
        fixed: true
    });
    displayAjaxLoading(false);
}
function ShowPoupCartDetail(html){
    displayAjaxLoading(true);
    jQuery.colorbox({
        width: 840,
        href:html,
        fixed: true
    });
    displayAjaxLoading(false);
}
var AjaxCart = {
    membershipAddToCart:function(id, time){
        var valid = true;
        if(!is_user_logged_in){
            valid = false;
            setLocation(loginUrl);
        }
        if(valid){
            displayAjaxLoading(true);
            jQuery.ajax({
                url: ajaxurl, type: "POST", dataType: "json", cache: false,
                data: {
                    action: 'membershipAddToCart',
                    id: id,
                    time: time,
                    locale: lang
                },
                success: function(response, textStatus, XMLHttpRequest){
                    if(response && response.status === 'success'){
                        setLocation(membershipCheckoutUrl);
                    }else if(response.status === 'error'){
                        displayBarNotification(true, "nWarning", response.message);
                    }
                },
                error: function(MLHttpRequest, textStatus, errorThrown){},
                complete:function(){
                    displayAjaxLoading(false);
                }
            });
        }
    },
    membershipOrderComplete:function(data){
        displayAjaxLoading(true);
        jQuery.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false, data: data,
            success: function (response) {
                if(response && response.status == 'success'){
                    displayBarNotification(true, "nSuccess", response.message);
                    setTimeout(function(){
                        setLocation(siteUrl);
                    }, 10000);
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                    if(response.login && response.login == 1){
                        setLocation(loginUrl);
                    }
                }else if(response.status == 'failure'){
                    displayBarNotification(true, "nFailure", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete: function(){
                displayAjaxLoading(false);
            }
        });
    },
    membershipOrderNganLuong:function(data){
        displayAjaxLoading(true);
        $.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false, data: data,
            success: function (response) {
                if(response && response.status == 'success'){
                    displayBarNotification(true, "nSuccess", response.message);
                    setTimeout(function(){
                        setLocation(response.nganluongUrl);
                    }, 1500);
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                    if(response.login && response.login == 1){
                        setLocation(loginUrl);
                    }
                }else if(response.status == 'failure'){
                    displayBarNotification(true, "nFailure", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete: function(){
                displayAjaxLoading(false);
            }
        });
        return false;
    },
    addToCart:function(id, title, price){
        var valid = true;
        if(!is_user_logged_in){
            valid = false;
            alert('Bạn sẽ được chuyển đến trang đăng nhập trước khi đăng ký khoá học này!');
            setLocation(loginUrl);
        }
        if(valid){
            displayAjaxLoading(true);
            jQuery.ajax({
                url: ajaxurl, type: "POST", dataType: "json", cache: false,
                data: {
                    action: 'addToCart',
                    id: id,
                    title: title,
                    price: price,
                    locale: lang
                },
                success: function(response, textStatus, XMLHttpRequest){
                    if(response && response.status === 'success'){
                        setLocation(cartUrl);
                    }else if(response.status === 'error'){
                        displayBarNotification(true, "nWarning", response.message);
                    }
                },
                error: function(MLHttpRequest, textStatus, errorThrown){},
                complete:function(){
                    displayAjaxLoading(false);
                }
            });
        }
    },
    deleteItem:function(product_id){
        displayAjaxLoading(true);
        jQuery.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false,
            data: {
                action: 'deleteCartItem',
                id: product_id,
                locale: lang
            },
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status == 'success'){
                    jQuery(".cart-price span.total_price").html(response.totalAmount);
                    jQuery("#product_item_" + product_id).remove();
                    displayBarNotification(true, "nSuccess", response.message);
                    reload = true;
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                }
            },  
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete:function(){
                displayAjaxLoading(false);
            }
        }); 
    },
    updateItem:function(product_id, time){
        displayAjaxLoading(true);
        jQuery.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false,
            data: {
                action: 'updateCartItem',
                id: product_id,
                time: time,
                locale: lang
            },
            success: function(response, textStatus, XMLHttpRequest){
                if(response && response.status === 'success'){
                    jQuery("#product_item_" + product_id + " .product-subtotal").html(response.item_amount);
                    jQuery(".cart-price span.total_price").html(response.totalAmount);
                    displayBarNotification(true, "nSuccess", response.message);
                    reload = true;
                }else if(response.status === 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete:function(){
                displayAjaxLoading(false);
            }
        });
    },
    preCheckout:function(){
        displayAjaxLoading(true);
        jQuery.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false,
            data: {
                action: 'preCheckout',
                locale: lang
            },
            success: function (response) {
                if(response && response.status == 'success'){
                    setLocation(checkoutUrl);
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete: function(){
                displayAjaxLoading(false);
            }
        });
    },
    orderComplete:function(data){
        displayAjaxLoading(true);
        jQuery.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false, data: data,
            success: function (response) {
                if(response && response.status == 'success'){
                    displayBarNotification(true, "nSuccess", response.message);
                    setTimeout(function(){
                        setLocation(siteUrl + "/shop");
                    }, 10000);
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                    if(response.login && response.login == 1){
                        setLocation(loginUrl);
                    }
                }else if(response.status == 'failure'){
                    displayBarNotification(true, "nFailure", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete: function(){
                displayAjaxLoading(false);
            }
        });
    },
    orderNganLuong:function(data){
        displayAjaxLoading(true);
        $.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false, data: data,
            success: function (response) {
                if(response && response.status == 'success'){
                    displayBarNotification(true, "nSuccess", response.message);
                    setTimeout(function(){
                        setLocation(response.nganluongUrl);
                    }, 1500);
                }else if(response.status == 'error'){
                    displayBarNotification(true, "nWarning", response.message);
                    if(response.login && response.login == 1){
                        setLocation(loginUrl);
                    }
                }else if(response.status == 'failure'){
                    displayBarNotification(true, "nFailure", response.message);
                }
            },
            error: function(MLHttpRequest, textStatus, errorThrown){},
            complete: function(){
                displayAjaxLoading(false);
            }
        });
        return false;
    },
    usingCoupon: function (coupon_code, total_amount) {
        displayAjaxLoading(true);
        $.ajax({
            url: ajaxurl, type: "POST", dataType: "json", cache: false,
            data: {
                action: 'usingCoupon',
                coupon_code: coupon_code,
                total_amount: total_amount,
                locale: lang
            },
            success: function (response, textStatus, XMLHttpRequest) {
                if (response && response.status == 'success') {
                    $(".cart-price .total_price").html(response.totalAmount);
                    $(".discount-amount").show().html(response.couponAmount);
                } else if (response.status == 'error') {
                    displayBarNotification(true, "nFailure", response.message);
                }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
            },
            complete: function () {
                displayAjaxLoading(false);
            }
        });
    }
};
jQuery(document).ready(function($){
    $("#nNote").click(function(){
        $(this).attr('class', '').hide("slow").html("");
    });
    $("#coupon_code").change(function (){
        var coupon_code = $(this).val().trim();
        if(coupon_code.length > 0){
            AjaxCart.usingCoupon(coupon_code, $("#total_amount").val().trim());
        }
    });
});