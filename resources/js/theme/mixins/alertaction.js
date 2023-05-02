export default {
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
             
              if (functionName == 'CancelExchangeOrderRequest') {
                 self.CancelExchangeOrderRequest(index); // index = order id 
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
             
             
            }else if (result.dismiss === Swal.DismissReason.cancel) {
              } 
        });

    },

  },
}