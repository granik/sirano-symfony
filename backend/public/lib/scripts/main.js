$(function(){function e(){return $.cookie("welcome","accepted",{expires:365,path:"/"}),$(".sirano-welcome").fadeTo("medium","0",function(){$(".sirano-welcome").hide()}),!1}$(".mobile-menu-switcher").on("click",function(){return $("body").toggleClass("menu-mobile-opened"),!1}),$(".menu-element.arrow .menu-element__link").on("click",function(){return $(this).closest(".menu-element").hasClass("submenu-opened")?($(this).closest(".menu-element.arrow").find(".submenu").slideUp("medium"),$(this).closest(".menu-element.arrow").removeClass("submenu-opened")):($(".menu-element.arrow .submenu").slideUp("medium"),$(".menu-element.arrow").removeClass("submenu-opened"),$(this).closest(".menu-element.arrow").find(".submenu").slideDown("medium"),$(this).closest(".menu-element.arrow").addClass("submenu-opened")),!1}),$(".sirano-welcome__accept").on("click",e),$(document).ready(function(){setTimeout(function(){e()},5e3)}),$(".additional-menu__profile > a").on("click",function(){return $(".additional-menu").toggleClass("menu-opened"),!1}),$(".menu-directions input[type=radio]").on("change",function(){return $(this).closest("form").submit(),!1}),$(".modal").on("show.bs.modal",function(){$(".modal").not($(this)).each(function(){$(this).modal("hide"),setTimeout(function(){$("body").addClass("modal-open")},200)})}),$('a[aria-controls="conference"], a[aria-controls="webinar"]').on("shown.bs.tab",function(e){$(e.target).attr("aria-controls")&&($("#all-conference, #all-webinar").toggleClass("d-none").toggleClass("d-block"),$("#conference-slider").slick("setPosition"),$("#webinar-slider").slick("setPosition"))}),$(".page-header-advanced__filters select,        .page-header-advanced__filters input,        .page-header-advanced__additions select").on("change",function(){$(this).closest("form").submit()}),$(".page-header-advanced__filters-toggler,        .page-header-advanced__filters-mobile__fade,        .page-header-advanced__filters-mobile__close").on("click",function(){return $("body").toggleClass("filters-mobile-opened"),$(".page-header-advanced__filters-mobile").toggleClass("d-flex"),!1}),$(".page-news-item__share-mobile a").on("click",function(){return $(".page-news-item__share").toggleClass("d-flex"),!1}),$('a[data-type="submit"]').on("click",function(){return $(this).closest("form").submit(),!1}),$('[data-type="webinar-subscribe-card"]').on("click",function(){var e=$(this),t=e.closest(".template-webinar__footer-buttons"),o=e.closest("form"),i=e.closest(".template-webinar__item-card"),a=i.find("[data-title]").text(),n={id:o.find('[name="id"]').val()};return $.ajax({type:"POST",url:o.attr("action"),data:n,dataType:"json",success:function(e,o,n){"ok"===e.status&&(t.html('<div class="registered">Вы зарегистрированы<br />на онлайн-трансляцию</div>'),i.addClass("active"),$("#modal-event-register-success").modal("show"),$("#modal-event-register-success").find(".event-name").text(a))},error:function(e,o,n){}}),!1}),$('[data-type="conference-subscribe-card"]').on("click",function(){var e=$(this),t=e.closest(".template-conference__footer-buttons"),o=e.closest("form"),i=e.closest(".template-conference__item-card"),a=i.find("[data-title]").text(),n={id:o.find('[name="id"]').val()};return $.ajax({type:"POST",url:o.attr("action"),data:n,dataType:"json",success:function(e,o,n){"ok"===e.status&&(t.html('<div class="registered">Вы зарегистрированы<br />на посещение</div>'),i.addClass("active"),$("#modal-event-register-success").modal("show"),$("#modal-event-register-success").find(".event-name").text(a))},error:function(e,o,n){}}),!1}),$('[data-type="webinar-subscribe"],        [data-type="webinar-unsubscribe"],        [data-type="conference-subscribe"],        [data-type="conference-unsubscribe"]').on("click",function(){var e=$(this).closest("form"),o={id:e.find('[name="id"]').val()};return $.ajax({type:"POST",url:e.attr("action"),data:o,dataType:"json",success:function(e,o,n){"ok"===e.status&&location.reload()},error:function(e,o,n){}}),!1}),$(".radio-dropdown").length&&$(".radio-dropdown").each(function(){if($(this).find("input[type=radio]:checked")){var e=$(this).find("input[type=radio]:checked").siblings("span").text();$(this).find(".radio-dropdown__link").text(e)}}),$(document).on("click",".radio-dropdown .radio-dropdown__link",function(){return $(this).closest(".radio-dropdown").hasClass("radio-dropdown__disabled")||($(this).closest(".radio-dropdown").hasClass("radio-dropdown__opened")?($(this).closest(".radio-dropdown").find(".radio-dropdown__submenu").slideUp("medium"),$(this).closest(".radio-dropdown").removeClass("radio-dropdown__opened")):($(".radio-dropdown__submenu").slideUp("medium"),$(".radio-dropdown").removeClass("radio-dropdown__opened"),$(this).closest(".radio-dropdown").find(".radio-dropdown__submenu").slideDown("medium"),$(this).closest(".radio-dropdown").addClass("radio-dropdown__opened"))),!1}),$(document).on("change",".radio-dropdown input[type=radio]",function(){var e=$(this).siblings("span").text();return $(this).closest(".radio-dropdown").find(".radio-dropdown__link").text(e),$(this).closest(".radio-dropdown").find(".radio-dropdown__submenu").slideUp("medium"),$(this).closest(".radio-dropdown").removeClass("radio-dropdown__opened"),!1}),$(document).on("change",".mobile-direction-filter .radio-dropdown input[type=radio]",function(){var e=$(this);$directionSelect=e.closest(".radio-dropdown"),$categoryTarget=$(".mobile-category-filter"),$.ajax({url:$directionSelect.data("category-url"),data:{direction:e.val()},success:function(e){$categoryTarget.html(e),$categoryTarget.find(".radio-dropdown").find(".radio-dropdown__link").text("Все категории")}})}),$("[data-share]").on("click",function(){var e=$(this).attr("data-url"),o="menubar=0, toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, width=700, height=400, left="+(screen.width-700)/2+", top="+(screen.height-400)/2;return e&&window.open(e,"new window",o),!1})});