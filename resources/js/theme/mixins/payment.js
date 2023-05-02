export default {
  data() {
    return {
    }
  },
  created: function () {
  },
  methods: {
    placeOrder(){
         let payload = {
            orderNote: this.cashfreeForm.orderNote,
            couponcode: this.couponCode,
            voucherAmount: this.cartcalulatedata.voucherAmount 
         };
         let section = $('#placeOrder');
         blockSection(section);
         openLoader();
         if(this.activePaymentMethod == 1){
            //razorpay
            this.$store.dispatch("globalStore/GetRazorpayDetails", payload)
            .then((res) => {
               if (res.response.status_code == 2066) {
                  this.razorpayOptions = {...res.response.data};
                  setTimeout(function(){
                     var rzp = new Razorpay(this.razorpayOptions);
                     rzp.open();
                  }.bind(this), 2000);
               } 
               else if (res.response.status_code == 2096) {
                  this.$toast.open({
                     message: res.response.message,
                     type: "error",
                  });
               }
               closeLoader();
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
               this.$toast.open({
                 message: err,
                 type: "error",
               });
            });
         } 
         else if(this.activePaymentMethod == 2){
            //paytm
            this.$store.dispatch("globalStore/ProcessPaytm", payload)
            .then((res) => {
               if (res.response.status_code == 2066) {
                  this.paytmData = {...res.response.data};
                  setTimeout(function(){
                     this.$refs.paytmForm.submit();
                  }.bind(this), 2000);
               }
               else if (res.response.status_code == 2096) {
                  this.$toast.open({
                     message: res.response.message,
                     type: "error",
                  });
               }
               closeLoader();
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
               this.$toast.open({
                 message: err,
                 type: "error",
               });
            });
         } 
         else if(this.activePaymentMethod == 3){
            //payzed
            alert('IN Progress');
         }
         else if(this.activePaymentMethod == 4){
            //cashfree
            this.$store.dispatch("globalStore/GetCashFreeFormDetails", payload)
            .then((res) => {
               if (res.response.status_code == 2066) {
                  this.cashfreeForm = {...res.response.data};
                  setTimeout(function(){
                     this.$refs.cashfreeForm.submit();
                  }.bind(this), 2000);
               }
               else if (res.response.status_code == 2096) {
                  this.$toast.open({
                     message: res.response.message,
                     type: "error",
                  });
               }
               closeLoader();
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
               this.$toast.open({
                 message: err,
                 type: "error",
               });
            });
         }
         else if(this.activePaymentMethod == 5){
            //instamojo
            this.$store.dispatch("globalStore/ProcessInstamojo", payload)
            .then((res) => {
               if (res.response.status_code == 2066) {
                     window.location.href = res.response.data.longurl;
               }
               else if (res.response.status_code == 2096) {
                  this.$toast.open({
                     message: res.response.message,
                     type: "error",
                  });
               }
               closeLoader();
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
               this.$toast.open({
                 message: err,
                 type: "error",
               });
            });
         }
         else if(this.activePaymentMethod == 6){
            //cash on delivery
            this.$store.dispatch("globalStore/ProcessCashOnDelhivery", payload)
            .then((res) => {
               if (res.response.status_code == 3119) {
                  this.$store.dispatch("globalStore/CodOrderSuccess", res.response.data)
                  .then((res) => {
                     if (res.response.status_code == 3120) {
                         window.location.href = res.response.data.url;
                     }
                     else if(res.response.status_code == 7013) {
                         window.location.href = res.response.data.url;
                     }
                     closeLoader();
                     unblockSection(section);
                  })
                  .catch((err) => {
                     unblockSection(section);
                     errorModal(err.response.message);
                     this.$toast.open({
                       message: err,
                       type: "error",
                     });
                  });
               }
               else if (res.response.status_code == 2096) {
                  this.$toast.open({
                     message: res.response.message,
                     type: "error",
                  });
               }
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
               this.$toast.open({
                 message: err,
                 type: "error",
               });
            });
         }
      },
  },
}

