export default {
    addToCart(state, payload) {
        let found = false;

        //if already exist then update quantity
        state.cart.products.forEach((item, i) => {
            if(item.id == payload.id){
                item.quantity = payload.quantity;
                found = true;
                return false;
            }
        });

        if(!found){
            state.cart.products.push(payload);
        }
    },
    setCartData(state, payload) {
        state.cart = payload;
    },
    removeCartProduct(state, cartProductID) {
        state.cart.products = state.cart.products.filter((cart) => { 
            return cart.id != cartProductID;
        });
    },
    clearCart(state) {
        state.cart.products = [];
    },
    decreaseQuantity(state, id) {
        let index = null;
        state.cart.products.forEach((item, i) => {
            if(item.id == id){
                let quantity = item.quantity - 1;
                if(quantity == 0){
                    index = i;
                } else {
                    item.quantity = item.quantity - 1;
                }
            }
        });

        if(index != null){
            state.cart.products.splice(index, 1);
        }
        if(state.cart.products.length == 0)
        {
            window.location.href = "/cart";
        }
    },
    increaseQuantity(state, id) {
        state.cart.products.forEach((item, i) => {
            if(item.id == id){
                item.quantity = item.quantity + 1;
            }
        });
    },
    setUserData(state, payload) {
        state.user = payload;
    },
    setWishlistCount(state, payload){
        state.wishlistTotal = payload;
    },
    removeWishlistItem(state){
        state.wishlistTotal = state.wishlistTotal - 1;
    },
    addWishlistItem(state){
        state.wishlistTotal = state.wishlistTotal + 1;
    },
    removeShippingBillingStorage(state, payload = "extra"){
        if(localStorage.getItem('billingAddressId') != null)
        {
            localStorage.removeItem('billingAddressId');
        }
        if(localStorage.getItem('shippingAddressId') != null)
        {
            localStorage.removeItem('shippingAddressId');
        }

    }
}
