timely.define(["jquery_timely","ai1ec_config"],function(e,t){var n=function(){return e("#ai1ec_is_free").is(":checked")},r=function(){return e("#ai1ec_cost").val()!==""},i=function(r){var i=e(this).parents("table:eq(0)"),s=e("#ai1ec_cost",i),o=t.label_a_buy_tickets_url;n()?(s.attr("value","").addClass("ai1ec-hidden"),o=t.label_a_rsvp_url):s.removeClass("ai1ec-hidden"),e("label[for=ai1ec_ticket_url]",i).text(o)};return{handle_change_is_free:i,check_is_free:n,check_is_price_entered:r}});