function sendMail() {
    var params = {
      email: document.getElementById("email").value,
      message: document.getElementById("message").value,
    };
  
    const serviceID = "service_5myynaj";
    const templateID = "template_3541wg8";
  
      emailjs.send(serviceID, templateID, params)
      .then(res=>{
          document.getElementById("email").value = "";
          document.getElementById("message").value = "";
          console.log(res);
          alert("Your message sent successfully!!")
  
      })
      .catch(err=>console.log(err));
  
  }