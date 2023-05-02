<template>
   <div class="cart-table-prd pt-0">
      <div class="collapse-default prd-description w-100" >
      <div class="card" >
         <a
         href="#"
         id="headingCollapse"
         class="card-header text-primary text-center theme-bg-color justify-content-center text-light  font-weight-bold"
         data-toggle="collapse"
         role="button"
         data-target="#collapse"
         aria-expanded="false"
         aria-controls="collapse"
         >
         {{ lang.global.show_details }}
      </a>
      <div id="collapse" role="tabpanel" aria-labelledby="headingCollapse" class="collapse theme-back-bg-color">
         <div class="card-body">
            <div class="row">
               <div class="col-18 border-bottom pb-2">
                  <div class="description-table d-flex">
                     <div class="cart-table-prd-info">{{ lang.global.profile.exchangeorder.description }}</div>
                     <div class="cart-table-prd-qty">{{ lang.global.profile.exchangeorder.request_qty }}</div>
                     <div class="cart-table-prd-qty">{{ lang.global.profile.exchangeorder.approve_qty }}</div>
                     <div class="cart-table-prd-qty">{{ lang.global.profile.exchangeorder.date }}</div>
                     <div class="cart-table-prd-qty">{{ lang.global.profile.exchangeorder.image }}</div>
                  </div>
               </div>
               <div class="col-18 description-data">
                  <div class="exchange-orderdescription border-bottom" v-for="(description, index) in exchangeProductDescription" :key="index">
                     <div class="my-2">
                        <div class="d-flex">
                           <div class="cart-table-prd-info">
                              <label>{{ lang.global.profile.exchangeorder.request }} :</label>
                              <span class="cart-table-prd-info ml-1" v-if=" description.deleted_at != null"><strike >{{description.client_request}}</strike></span>
                              <span class="cart-table-prd-info ml-1" v-else>{{description.client_request}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info">{{description.exchangeClientQuantity}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info"></span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info">{{description.created_at}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <button type="button" class="btn btn-primary" @click="openModal(description.img_src)">{{lang.global.show}}
                              </button>
                           </div>
                        </div>
                        <div class="d-flex" v-if="description.exchangeApproveQuantity > 0">
                           <div class="cart-table-prd-info ml-1">
                              <label>{{ lang.global.profile.exchangeorder.response }} :</label>
                              <span class="cart-table-prd-info">{{description.admin_response}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info"></span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info">{{description.exchangeApproveQuantity}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                              <span class="cart-table-prd-info">{{description.updated_at}}</span>
                           </div>
                           <div class="cart-table-prd-qty">
                           </div>
                        </div>
                     </div>

                     <!-- image modal start  -->
                     <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" id="exampleModalLongTitle">Image Media</h5>
                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 </button>
                              </div>
                              <div class="modal-body">
                                 <div class="row">
                                    <div class="col-md-4 mb-1" v-if="description_img_src.length > 0" v-for="(image , index) in description_img_src" :key="index">
                                          <a :href="image.img_src" target="_new">
                                             <img :src="image.img_src"  @error="setAltImg" height="150" width="120">
                                          </a>
                                    </div>
                                    <div v-else>
                                       <h2>No Image Found</h2>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- image modal end  -->

                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</template>

<script>
   import { mapState } from 'vuex';

   export default {
      name: "exchangeOrderDescription",
      props:['frompage','exchangeproductdes'],
      data() {
         return {
            exchangeProductDescription:[],
            description_img_src: [],

         }
      },

      computed: {
         ...mapState(['globalStore']),
         noImage(){
            return this.globalStore.no_image;
         }
      },
      mounted(){
         let self = this;
         setTimeout(function(){
            self.exchangeProductDescription = self.exchangeproductdes; 
         }, 500);


      },
      methods:{
         openModal(description_img_src){
          this.description_img_src = description_img_src;
          $("#imageModal").modal('show');
      },
         setAltImg(event){
            event.target.src = this.noImage;
         },

      }


   }
</script>
<style lang="scss" scoped>
   .exchange-orderdescription:last-child{
      border-bottom:none !important;
      margin-bottom:0px !important;
   }
   .table-dark
   {
      background-color: #283046;
   }
   .theme-bg-color{
      background-color: #283046;
   }
   .theme-back-bg-color{
      background-color: #f8f8f8;
   }
   .card{
      border:none;
   }
   .card-body{
      border:1px solid #e1dfdf;
   }
   .cart-table-prd:nth-child(2){
      border-bottom:none;
   }
   .cart-table-prd{
      padding-left:10px !important;
      padding-right:10px !important;
   }
   .close{
      right:0px;
      top:0px;
   }
</style>