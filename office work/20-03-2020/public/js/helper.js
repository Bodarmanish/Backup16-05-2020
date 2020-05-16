function detectClientTZ(lt,lto) {
    var today = new Date();
    var offset = -today.getTimezoneOffset();
    var jan = new Date(today.getFullYear(), 0, 1);
    var jul = new Date(today.getFullYear(), 6, 1);
    var dst = today.getTimezoneOffset() < Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
    var offset_hrs = (offset / 60);
    var dst = (+dst);
    var fd = new FormData();

    fd.append('offset', offset);
    fd.append('offset_hrs', offset_hrs);
    fd.append('dst', dst);

    if(lt == "" || lto != offset)
    {
        $.ajax({
            url: "/localtz",
            type: 'post',
            data: fd,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response){ console.log("Local Timezone: "+response.local_timezone) },
        });
    }
}

function setCookie(cname, cvalue, exdays){
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function reloadPage(){
    window.location.reload();
}

function refreshPage(){
    window.location.href = window.location.href;
}

function textCounter(limitField, limitCount, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    }
    else {
        limitCount.value = limitNum - limitField.value.length;
    }
}

/** Start array functions **/
function removeArrayElement(array, element) {
    const index = array.indexOf(element);

    if (index !== -1) {
        array.splice(index, 1);
    }
    return array;
}

function inArray(needle, haystack){
    var found = 0;
    for (var i=0, len=haystack.length;i<len;i++) {
        if (haystack[i] == needle) return i;
        found++;
    }
    return -1;
}
/** End array functions **/

