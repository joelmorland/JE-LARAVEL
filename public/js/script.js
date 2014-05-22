jQuery(document).ready(function() {
    //lets preload the ratings image to mitigate the lag between restaurant listings and ratings appearing
    $('<img/>')[0].src = '/img/stars.png';    
    //lets preload the logo image used for any broken restaurant image links
    $('<img/>')[0].src = '/img/justeat.jpg'; 
        jQuery('form#search').submit(function(e){
                e.preventDefault();
                submitAjaxForm($(this).serialize(),
                               '/restaurants',
                               'populateAreaResults',
                               $(this).children('input[type="submit"]'),
                               'gracefulFailure',
                               'json',
                               'GET');
            }); 
        
        $('#restaurants').on('click','a.getMenuCategories',function(e) {
            e.preventDefault();
            $(this).addClass("appendMe");
            submitAjaxForm({},
                           '/menuCategories/'+$(this).attr('restaurantId'),
                           'loadMenuCategories',
                           $('#dialog div.body'),
                           'menuFail',
                           'json',
                           'GET');
            setDialogTitle($(this).parent('div.footer').siblings('div.name').text());
        });                    
}); 

/**
  *
  */
function repairBrokenImage(image){
        //add the class and with CSS3, though can also set source for pre css3 browsers which don't accept content property 
        image.addClass('broken-image').attr('broken-src',image.attr('src')).attr('src','/static/img/justeat.jpg');
        image.removeAttr('onerror');
};       
    
function populateAreaResults(areaResults)
{
        if (typeof areaResults.error !='undefined') {
            $('#restaurants').html('<div class="error">'+areaResults.error+'</div>');
            return;
        } else if (typeof areaResults.Restaurants=='undefined' || areaResults.Restaurants.length==0){
            $('#restaurants').html('<div class="error">No Results</div>');
            return;
        }
        $('#restaurants').html('<div class="success">Restaurants delivering in your area</div>');
        orderedRestaurants=sortRestaurants(areaResults.Restaurants,1);
        for(r in orderedRestaurants) {
            addRestaurantResult(orderedRestaurants[r][1]);
        };
};
    
function sortRestaurants(obj, direction){
        array=[];
        for(a in obj){
         array.push([(obj[a]['RatingStars']*100),obj[a]]);
        }
        array.sort();
    
        if (direction<=0) return array;
        return array.reverse();
}    

function addRestaurantResult(details)
{
        if (typeof details.Name=='undefined') return;
        //order from highest average to lower
        restaurantHtml='<div class="restaurantHolder">';
        restaurantHtml+='<div class="logo"><a class="logoLink" href="'+details.Url+'"><img onerror="repairBrokenImage($(this));" src="'+fetchLogo(details.Logo)+'")/></a></div>';
        restaurantHtml+='<div class="details"><div class="name"><a href="'+details.Url+'">'+details.Name+'</a> ' + starRating(details.RatingStars,details.NumberOfRatings) + '</div>';
        restaurantHtml+='<div class="smallText">'+details.RatingStars+'/6 from '+details.NumberOfRatings+' ratings</div>';
        restaurantHtml+='<div class="footer"><a href="#" class="getMenuCategories" restaurantId="'+details.Id+'">Products Available</a></div>';
        restaurantHtml+='<div class="clear"></div></div>';
        $('#restaurants').append(restaurantHtml);
}
function fetchLogo(details)
{ 
        if (typeof details[0].StandardResolutionURL!='undefined' && details[0].StandardResolutionURL!='')
            return details[0].StandardResolutionURL;
        else
            return '/static/img/justeat.jpg';    
}
function loadMenuCategories(ajaxCategories)
{
        var categories = "<div><ul class=''>";
        $.each(ajaxCategories.Categories, function(k,v){
            categories+="<li class='category'><h3>"+v.Name+"</h3>";
            if (v.Notes!='')
                categories+="<div class='smallText'>"+v.Notes+"</div>";
            categories+="</li>";
        });
        categories+="</ul></div>";
        $('#dialog .body').html(categories);
        $('#dialog').dialog({ modal: true,
                              minWidth: 400,
                              maxHeight: $(window).height()*2/3,                           
                           });
}
function starRating(rating,numberOfRatings)
{
        return "<span class='ratings' title='"+rating+"/6 from "+numberOfRatings+" ratings'><span style='width: "+(Math.max(0, (Math.min(6, rating))) * 16)+"px;' /></span>";
}
function toggleLoader(test)
{
        if ($('#loader').is(':visible'))
            $('#loader').hide();
        else
            $('#loader').css({left:'50%',top:$(document).scrollTop()+($(window).height()/2)+'px'}).show(); 
}    
function setDialogTitle(title) {
        $('#dialog').dialog({title:title});
}
function submitAjaxForm(formData,destination,callback,loaderElement,failureCallback,dataType,requestType)
{   
        toggleLoader(loaderElement);
        $.ajax({
               type: 'POST',
               url: destination,
               data: formData,
               dataType: dataType,
               success: function(data, textStatus, jqXHR) {
                    if (textStatus!='success')
                        return false;
                    if (callback!==false && typeof window[callback]=='function'){
                        fn = window[callback];
                        return fn(data);
                    } else {
                        return data;
                    }
               },
              error: function(data, textStatus, jqXHR) {
                  if (typeof data.responseJSON!='undefined' && typeof data.responseJSON.errorAlertMessage!='undefined')
                    alert(data.responseJSON.errorAlertMessage);
                  if (failureCallback!==false && typeof window[failureCallback]=='function'){
                        fn = window[failureCallback];
                        return fn(data);
                  } else {
                        return false;
                  }
                },
                complete: function(data) {
                    toggleLoader(loaderElement);
                }});
}

