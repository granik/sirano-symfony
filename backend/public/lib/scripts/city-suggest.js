$(function(){var e=$("#"+cityInputId);function a(t,n){var i=1<arguments.length?n:", ";return t.filter(function(t){return t}).join(i)}function n(){$("input#"+formPrefix+"_kladrId").val(null),$("input#"+formPrefix+"_country").val(null),$("input#"+formPrefix+"_fullCityName").val(null)}e.suggestions({token:dadataToken,type:"ADDRESS",hint:!1,bounds:"city-settlement",onSuggestionsFetch:function(t){return t.filter(function(t){return null===t.data.city_district&&"65"!==t.data.fias_level})},onSelect:function(t){var n,i="";n=t.data.settlement?t.data.settlement_with_type:"г"!==t.data.city_type?t.data.city_with_type:t.data.city,"г"!==t.data.region_type&&(i=t.data.region+" "+t.data.region_type_full+", "),t.data.area&&(i=i+t.data.area+" "+t.data.area_type_full+", "),t.data.city_district&&(i=i+t.data.city_district+" "+t.data.city_district_type_full+", "),i+=n,$("input#"+formPrefix+"_kladrId").val(t.data.kladr_id),$("input#"+formPrefix+"_country").val(t.data.country),$("input#"+formPrefix+"_cityName").val(n),$("input#"+formPrefix+"_fullCityName").val(i),e.val(n)},onSelectNothing:function(t){n()},onInvalidateSelection:function(t){n()}}),dadataGeoLocation&&void 0!==e.suggestions().getGeoLocation()&&e.suggestions().getGeoLocation().done(function(t){var n,i={value:null,date:t};e.suggestions().setSuggestion(i),e.val(a([a([(n=t).city_type,n.city]," "),a([n.settlement_type,n.settlement]," ")]))}),$("#dadata-foreign-city").on("change",function(){this.checked?e.suggestions().setOptions({constraints:{locations:{country:"*"}}}):e.suggestions().setOptions({constraints:{locations:{}}})}),$("#"+formPrefix+"_foreignCity").on("change",function(){this.checked?e.suggestions().setOptions({constraints:{locations:{country:"*"}}}):e.suggestions().setOptions({constraints:{locations:{}}})})});