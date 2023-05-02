export default {
  data() {
    return {
      msg: 'Hello World',
    }
  },
  created: function () {
  },
  methods: {
    confirmDeleteBox(text,functionName,index = 0){
          let self = this
            Swal.fire({
            title: 'Are you sure?',
            text: "You want to  "+ text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ml-1'
            },
             buttonsStyling: false
        })
         .then(function (result) {
            if (result.value) {
              if(functionName == 'deleteShippingOrder'){
                self.deleteShippingOrder();
              }
              else if (functionName == 'removeProduct') {
                 self.removeProduct(index);
              }
              else if (functionName == 'removeShippingProduct') {
                 self.removeShippingProduct(index);
              }
              else if (functionName == 'removeReturnProduct') {
                 self.removeReturnProduct(index);
              }
              else if (functionName == 'deleteReturnShippingOrder') {
                 self.deleteReturnShippingOrder();
              }
              else if (functionName == 'removeReturnShippingProduct') {
                 self.removeReturnShippingProduct(index);
              }
              else if (functionName == 'deleteOrder') {
                 self.deleteRecord();
               }
              else if (functionName == 'deleteInvoice') {
                 self.deleteInvoice();
              }
              else if (functionName == 'deleteRates') {
                 self.deleteRates(index);
              }
            }else if (result.dismiss === Swal.DismissReason.cancel) {
              } 
        });

    },
    confirmCancelBox(text,functionName,index = 0){
          let self = this
            Swal.fire({
            title: 'Are you sure?',
            text: "You want to "+ text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ml-1'
            },
             buttonsStyling: false
        })
         .then(function (result) {
            if (result.value) {
             
              if (functionName == 'cancelShippingOrder') {
                 self.cancelShippingOrder();
              }
              
              else if (functionName == 'cancelReturnShippingOrder') {
                 self.cancelReturnShippingOrder();
              }
              
              else if (functionName == 'cancelExchangeOrder') {
                 self.cancelExchangeOrder();
              }
              
            }else if (result.dismiss === Swal.DismissReason.cancel) {
              } 
        });

    },
    confirmPickupBox(text,functionName,index = 0){
          let self = this
            Swal.fire({
            title: 'Are you sure?',
            text: "You want to "+ text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, pickup it!',
            customClass: {
              confirmButton: 'btn btn-primary',
              cancelButton: 'btn btn-outline-danger ml-1'
            },
             buttonsStyling: false
        })
         .then(function (result) {
            if (result.value) {
             
              if (functionName == 'pickupOrder') {
                 self.pickupOrder();
              }
              
               else if (functionName == 'pickupReturnOrder') {
                 self.pickupReturnOrder();
              }
             
            }else if (result.dismiss === Swal.DismissReason.cancel) {
              } 
        });

    },

  },
}