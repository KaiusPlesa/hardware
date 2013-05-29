$(document).ready(function(){
    /*
$('#wysihtml5').wysihtml5({
    "events": {
        "load": function() { 
            console.log("Loaded!");
        },
        "blur": function() { 
            console.log("Blured");
        }
    }
});
*/
//onfocusin and out
 $("#focus_id").blur(function() {        
        $("#addBlogForm").validate().element( "#categ_h_id" );
        }); 
    
$.validator.setDefaults({ 
    ignore: [],
    // any other default options and/or rules
});

jQuery.validator.addMethod("textareaEditor", function(value, element) {
    
    return $('#wysihtml5').val(); 
});

jQuery.validator.addMethod("categselected", function() {
    return $("#addBlogForm").validate().element( "#categ_h_id" );
});

    
$.validator.addMethod(
    "regex",
    function (value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
"invalid"
);
   
//FORM BLOG EDIT 
$("#formSignUp").validate({
              
        rules: {
            username: {
                required: true,
                minlength:5                          
            },
             email: {
                required: true,
                email: true           
            },  
            display_name: {
                required: true,
                minlength:5,
                maxlength:100           
            },
             password: {
                required: true,
                minlength:6,
                maxlength:15
                           
            },
            passwordVerify: {
                equalTo: "input[name='password']"                          
            }                
        },
        messages: {
            username: {
                required: "This field is required.",
                minlength:"Minimum Lenght is 5 characters."
            },
            email: {
                required: "This field is required.",
                email:"Please enter a valid email address."
            },
             display_name: {
                required: "This field is required.",
                minlength:"Minimum Lenght is 5 characters",
                maxlength:"Maximum Lenght is 100 characters"
            },
            password: {
                required: "This field is required.",
                minlength:"Minimum Lenght is 6 characters",
                maxlength:"Maximum Lenght is 15 characters"               
            },
             passwordVerify: {
                equalTo: "Passwords do not match."
                              
            }                           
            
        },
       
      errorPlacement: function(error, element) {                
            elementName = $(element).attr("name");
            $("#error_"+elementName).html(error);         
            //$('iframe').contents().find('.wysihtml5-editor').html();                 
            },
      highlight: function(element) {
            $(element).closest('.liNoP').removeClass('success').addClass('error');
            },                              
      unhighlight: function(element) {
            $(element).closest('.liNoP').removeClass('error').addClass('success');
            $(element).closest('form').find('.valid').removeClass("invalid");
            },
      success: function(element) {
            $(element).closest('.liNoP').removeClass('error').addClass('success');
            $(element).closest('form').find('.valid').removeClass("invalid");
            },                                  
    });       
   
     
});




