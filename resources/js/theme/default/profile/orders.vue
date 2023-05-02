<template>
  <div class="order-history-table" id="orderSection">
    <h1 class="mb-3 text-center">{{ lang.global.profile.orders.order_history }}</h1>
    <div id="orderSection">
          <div class="card border-0">
            <div class="card-body">
                <div class="row mb-2 prd-list pointer" v-for="(product, index) in orderData" v-if="getOrderLength" :step="count = index + 1" @click="ViewOrderDetail(product.order_id,product.id)" :id="`orderSection_${product.id}`" @mouseover="enterSection(product.id)" @mouseleave="outSection(product.id)"> 
                  <div class="prd-image"> 
                    <div class="cart-table-prd-image">
                      <img class="lazyload fade-up" :src="product.image_src[2]" :data-src="product.image_src[2]" :alt="product.slug" @error="setAltImg">
                  </div>
                  </div>
                  <div class="prd-info">
                    <h3 class="cart-table-prd-name mb-1">
                      {{ product.title }}
                    </h3>
                    <div class="d-flex">
                      <span class="minicart-qty">{{ product.quantity }}</span>
                      <span class="minicart-qty bg-danger" v-if="product.remainQuantity > 0">{{ product.remainQuantity }}</span>
                    </div>
                  </div>
                  <div class="prd-price">
                    <div class="pro-qty">{{product.symbol}} {{ (product.price * product.quantity) }}</div>
                  </div>
                  <div class="prd-date">
                    <h6>{{ lang.global.profile.orders.order_created }}</h6>{{product.created_at}}
                  </div>
                </div>
                <div class="section-pagination" v-if="orderData.length > 0">
                  <sliding-pagination
                   :current="currentPage"
                   :total="totalPages"
                   @page-change="pageChangeHandler"
                   >
                  </sliding-pagination>
                </div> 
                <div class="row" v-if="!getOrderLength">
                  <div class="col-12 text-center">{{ lang.global.profile.orders.get_order_length }}</div>
                </div> 
            </div>
          </div>

    </div>
  </div>
</template>

<script> 
import { mapState } from 'vuex'
  export default {
    name: "order",
    props: ['data','list'],
    data() {
      return {
        client_id: CLIENT_ID,
        cruds: [],
        orderData: [],
        currentPage: 1,
        timeStatus:30,
        totalPages: 1,
        totalRecords: 0,
      }
    },
    computed:{
       ...mapState(['globalStore']),
      getOrderLength(){
          return (this.orderData.length > 0 ) ? true : false;
      },
      noImage(){
         return this.globalStore.no_image;
      }
    },
    mounted(){
      this.totalPages = this.totalpages;
      this.totalRecords = this.totalrecords;
      this.GetUserOrder();
    },
    methods: {
          pageChangeHandler(selectedPage) {
            this.currentPage = selectedPage;
            // openLoader();
            // let payload = {
            //    page: this.currentPage,
            //    product_id: this.product.id
            // }
            // this.$store.dispatch("globalStore/GetReviewsData", payload)
            // .then((res) => {
            //    if (res.response.status_code == 3127) {
            //       this.objReviews = res.response.data.reviews;
            //    }
            //    closeLoader();
            // })
            // .catch((err) => {
            //    this.$toast.open({
            //       message: err,
            //       type: "error",
            //    });
            //    closeLoader();
            // });
         
         
      },
          ViewOrderDetail(order_id , order_product_id) {
              window.location.href = "order/" + order_id + "/" + order_product_id;
           },
          GetUserOrder(){
            let section = $('#orderSection');
            blockSection(section);
            this.$store.dispatch("globalStore/GetOrders",{'currentPage': this.currentPage})
           .then((res) => {
                unblockSection(section);
                if (res.response.status_code == 2082) {
                  this.orderData = res.response.data.orderProducts;
                  this.currentPage = res.response.data.currentPage;
                }
            })
            .catch((err) => {
              unblockSection(section);
              this.$toast.open({
                message: err,
                type: "error",
              });
            });
        },
        enterSection(product_id)
        {
          $("#orderSection_"+product_id).addClass('bg-change-order');
        },
        outSection(product_id){
          $("#orderSection_"+product_id).removeClass('bg-change-order');
        },
        setAltImg(event){
         event.target.src = this.noImage;
        },

    },
  }
</script>
<style lang="scss">
  .bg-change-order{
    background: #ede7e7;
  }
  .prd-list{
    border:1px solid #f4f4f4;
    border-radius:2px;
    padding:20px 10px;
  }
 .prd-image{
    flex-basis:15%;
    text-align:center;
    img{
      height:100px;
      width:90px;
    }
  }
  .prd-date{
    flex-basis:30%;
  }
  .prd-info{
        flex-basis: 40%;
        padding: 0px 10px;
        .minicart-qty{
          position: relative;
          color: #fff;
          border-color: #17c6aa;
          background-color: #17c6aa;
          padding: 13px;
          font-size: 14px;
          margin-right:10px;
        }
  }
  .prd-price{

    flex-basis: 15%;
    text-align: center;
  }

  .c-sliding-pagination__list{
      display: flex;
      padding-left: 0;
      list-style: none;
      border-radius: .25rem;
      float: right;
      margin-top: 30px;
    .c-sliding-pagination__list-element{
      margin-right: 15px;
      &:hover{
        border-radius: 5px;
        background: #efefef;
      }
      .c-sliding-pagination__page{
        &:hover{
          text-decoration: none !important;
          font-weight: bold;
        }
      }
      a{
          font-size: 18px;
          font-weight: 600;
          line-height: 36px;
          margin: 10px 1px;
          padding: 0 10px;
          transition: .2s;
          color: #282828;
      }
    }
    .c-sliding-pagination__list-element--active{
      background: #17c6aa;
      border-radius: 5px;
      &:hover{
        background: #17c6aa;
        color: #000;
      }
      .c-sliding-pagination__page--current{
        color: #fff;
        &:hover{
          color: #000;
        }
      }
    }
  }
</style>
