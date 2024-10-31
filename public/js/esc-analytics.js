class ESC_Analytics {
  constructor(options = {}){    
    this.options = {
      esc_tracking: true
    };
    if(options){
      Object.assign(this.options, options);
    } 
    console.log(this.options);
    //this.addEventBindings();  
  }
  addCheckoutEventBindings(){
   // var first_name = document.getElementsByClassName("billing_first_name");
    var first_name_ele = document.getElementById("billing_first_name");
    var last_name_ele = document.getElementById("billing_last_name");
    var billing_email_ele = document.getElementById("billing_email");

    var first_name,last_name,billing_email; 
    if(first_name_ele != null  && last_name_ele != null){
      first_name_ele.addEventListener('focusout', (event) => {
        first_name = first_name_ele.value;
      });
      last_name_ele.addEventListener('focusout', (event) => {
        last_name = last_name_ele.value;
        //console.log("first_name="+first_name+" last_name="+last_name+" URL="+this.options.esc_ajax_url);
        var postdata= {action:'update_checkout_data', esc_ajax_nonce:this.options.esc_ajax_nonce, checkout_step_1:'checkout_step_1', first_name:first_name, last_name:last_name};
        this.updateCheckoutFileds(postdata)
      });

      billing_email_ele.addEventListener('focusout', (event) => {
        billing_email = billing_email_ele.value;
        var postdata= {action:'update_checkout_data', esc_ajax_nonce:this.options.esc_ajax_nonce, checkout_step_1:'checkout_step_1', first_name:first_name, last_name:last_name, billing_email:billing_email};
        this.updateCheckoutFileds(postdata)
      });

      /* Checkout step 3 */
      var place_order = document.getElementById("place_order");
      place_order.addEventListener('click', (event) => {
        first_name = first_name_ele.value;
        last_name = last_name_ele.value;
        billing_email = billing_email_ele.value;
        var postdata= {action:'update_checkout_data', esc_ajax_nonce:this.options.esc_ajax_nonce, checkout_step_1:'checkout_step_1',checkout_step_3:'checkout_step_3', first_name:first_name, last_name:last_name, billing_email:billing_email};
        this.updateCheckoutFileds(postdata)
      });
      
    }
    
  }
  updateCheckoutFileds(postdata){    
    jQuery.ajax({
      type: "POST",
      dataType: "json",
      url: this.options.esc_ajax_url,
      data: postdata,
      success: function (response) {
        console.log(response);              
      }
    });    
  }

}