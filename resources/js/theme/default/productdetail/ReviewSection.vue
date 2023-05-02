<template>
   <div>
      <div id="productReviews">
         <div class="row align-items-center">
            <div class="col">
               <h2> {{ lang.global.productdetail.descriptionsection.customer_review }} </h2>
            </div>
            <div v-if="displayAction">
              <div class="col-18 col-md-auto mb-3 mb-md-0" v-if="(Object.keys(user).length > 0) && revieweditaccess == 1">
                  <a href="javascript:void(0)" data-toggle="modal" data-target="#reviewModal" class="review-write-link" >
                     <i class="icon-pencil"></i> {{ lang.global.write_review }}
                  </a>
               </div>
            </div>
            
         </div>
         <div id="productReviewsBottom">
            <div class="review-item mb-2" v-for="( review, index ) in objReviews">
               <div class="review-item-rating d-flex justify-content-between">
                  <star-rating 
                  :max-rating="5"
                  inactive-color="#dadada"
                  active-color="#efce4a"
                  :read-only="true"
                  v-bind:star-size="30"
                  :show-rating="false" 
                  v-model="review.star_rating">
                  </star-rating>
                  <div class="clearfix" v-if="review.review_edit_access == true" >
                     <a href="javascript:void(0)" @click="openReviewModal(index)">
                        <i class="icon-pencil"></i>{{ lang.global.edit_review }}
                     </a>
                  </div>
               </div>

               <div class="review-item-top row">
                  <div class="col">
                     <h5 class="review-item_author"> {{review.created_at}}</h5>
                  </div>
               </div>
               <div class="review-item-content mb-1">
                  <h4> {{ review.title }} </h4>
                  <p> {{ review.description }}</p>
               </div>
               <div class="review-image d-flex" v-if="review.media.length > 0">
                  <div class="mr-1" v-for="(image , index) in review.media" :key="index" @click="openImageModal(image.src)">
                     <img class="main-image" :src="image.src" height="80" width="80">
                  </div>
               </div>
            </div>

            <div class="section-pagination" v-if="reviews.length > 0">
               <sliding-pagination
               :current="currentPage"
               :total="totalPages"
               @page-change="pageChangeHandler"
               >
               </sliding-pagination>
            </div> 
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "ReviewSection",
      props:['product','user','reviews','totalrecords','totalpages','revieweditaccess'],
      data() {
         return {
            currentPage: 1,
            totalPages: 1,
            totalRecords: 0,
            objReviews: [],
            displayAction:true,
            userReview: [],
            
         }
      },
      mounted(){
         this.objReviews = this.reviews;
         this.totalPages = this.totalpages;
         this.totalRecords = this.totalrecords;
         if(this.revieweditaccess == 0){
            this.displayAction = false;
         }
      },
      created(){
      },  
      computed: {

      },
      methods: {
         openImageModal(src){
            this.$emit('openmodal',src);
            
         },
         openReviewModal(index){
            this.userReview = this.objReviews[index];
            this.$emit('openreviewmodal',index);
            
         },
         pageChangeHandler(selectedPage) {
            this.currentPage = selectedPage;
            openLoader();
            let payload = {
               page: this.currentPage,
               product_id: this.product.id
            }
            this.$store.dispatch("globalStore/GetReviewsData", payload)
            .then((res) => {
               if (res.response.status_code == 3127) {
                  this.objReviews = res.response.data.reviews;
               }
               closeLoader();
            })
            .catch((err) => {
               this.$toast.open({
                  message: err,
                  type: "error",
               });
               closeLoader();
            });
         },
         
      },
      components: {
      }
   }
</script>
