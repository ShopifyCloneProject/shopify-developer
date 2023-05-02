<template>
   <div class="holder mt-3 mt-md-5">
      <div class="container">
         <!-- Nav tabs -->
         <ul class="nav nav-tabs product-tab">
            <li class="nav-item"><a href="#Tab1" class="nav-link" data-toggle="tab"> {{ lang.global.productdetail.descriptionsection.description }}
               <span class="toggle-arrow"><span></span><span></span></span>
               </a>
            </li>
            <li class="nav-item"><a href="#Tab2" class="nav-link" data-toggle="tab" v-if="product.is_size_chart_enabled == 1"> {{ lang.global.productdetail.descriptionsection.sizing_guide }}
               <span class="toggle-arrow"><span></span><span></span></span>
               </a>
            </li>
            <li class="nav-item"><a href="#Tab3" class="nav-link" data-toggle="tab" v-if="variants.length > 0"> {{ lang.global.productdetail.descriptionsection.additional_information }}
               <span class="toggle-arrow"><span></span><span></span></span>
               </a>
            </li>
            <li class="nav-item"><a href="#Tab4" class="nav-link" data-toggle="tab"> {{ lang.global.productdetail.descriptionsection.assigned_tags }}
               <span class="toggle-arrow"><span></span><span></span></span>
               </a>
            </li>
            <li class="nav-item"><a href="#Tab5" class="nav-link" data-toggle="tab"> {{ lang.global.productdetail.descriptionsection.reviews }}
               <span class="toggle-arrow"><span></span><span></span></span>
               </a>
            </li>
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="Tab1">
               <div id="productDescription">
                  <h4 class="mb-15"> {{ lang.global.productdetail.descriptionsection.account_of_system }} </h4>
                  <div v-html="product.description"></div>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="Tab2" v-if="product.is_size_chart_enabled == 1">
               <h3> {{ lang.global.productdetail.descriptionsection.size_conversion }} </h3>
               <table class="table table-striped">
                  <tr>
                     <th scope="row"> {{ lang.global.productdetail.descriptionsection.us }} {{ lang.global.productdetail.descriptionsection.sizes }}</th>
                     <td>6</td>
                     <td>6,5</td>
                     <td>7</td>
                     <td>7,5</td>
                     <td>8</td>
                     <td>8,5</td>
                     <td>9</td>
                     <td>9,5</td>
                     <td>10</td>
                     <td>10,5</td>
                  </tr>
                  <tr>
                     <th scope="row"> {{ lang.global.productdetail.descriptionsection.euro }} {{ lang.global.productdetail.descriptionsection.sizes }} </th>
                     <td>39</td>
                     <td>39</td>
                     <td>40</td>
                     <td>40-41</td>
                     <td>41</td>
                     <td>41-42</td>
                     <td>42</td>
                     <td>42-43</td>
                     <td>43</td>
                     <td>43-44</td>
                  </tr>
                  <tr>
                     <th scope="row"> {{ lang.global.productdetail.descriptionsection.uk }} {{ lang.global.productdetail.descriptionsection.sizes }} </th>
                     <td>5,5</td>
                     <td>6</td>
                     <td>6,5</td>
                     <td>7</td>
                     <td>7,5</td>
                     <td>8</td>
                     <td>8,5</td>
                     <td>9</td>
                     <td>9,5</td>
                     <td>10</td>
                  </tr>
                  <tr>
                     <th scope="row"> {{ lang.global.productdetail.descriptionsection.inches }} </th>
                     <td>9.25&quot;</td>
                     <td>9.5&quot;</td>
                     <td>9.625&quot;</td>
                     <td>9.75&quot;</td>
                     <td>9.9375&quot;</td>
                     <td>10.125&quot;</td>
                     <td>10.25&quot;</td>
                     <td>10.5&quot;</td>
                     <td>10.625&quot;</td>
                     <td>10.75&quot;</td>
                  </tr>
                  <tr>
                     <th scope="row"> {{ lang.global.productdetail.descriptionsection.cm }} </th>
                     <td>23,5</td>
                     <td>24,1</td>
                     <td>24,4</td>
                     <td>24,8</td>
                     <td>25,4</td>
                     <td>25,7</td>
                     <td>26</td>
                     <td>26,7</td>
                     <td>27</td>
                     <td>27,3</td>
                  </tr>
               </table>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="Tab3" v-if="variants.length > 0">
               <div>
                  <table class="table table-striped">
                      <tr v-for=" (variant,index) in variants ">
                           <th>{{ variant.type }}:</th>
                           <td>{{ getVariants(variant.options) }}</td>
                      </tr>
                  </table>
               </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="Tab4">
               <ul class="tags-list" v-if="Object.keys(tags).length > 0">
                  <li v-for="( tag, index ) in tags"><a>{{ tag }}</a></li>
               </ul>
               <ul class="tags-list" v-else>
                  <li> {{ lang.global.productdetail.descriptionsection.assign_tag_not_found }} </li>
               </ul>
              <!--  <h3>Add your tag</h3>
               <form class="form--simple">
                  <label>Tag<span class="required">*</span></label>
                  <input class="form-control form-control--sm">
                  <button class="btn btn--md">Submit Tag</button>
                  <div class="required-text">* Required Fields</div>
               </form> -->
            </div>
            <div role="tabpanel" class="tab-pane fade" id="Tab5">
              <ReviewSection :reviews="reviews" :product="product" :user="user" :totalpages="totalpages" :totalrecords="totalrecords" :revieweditaccess="revieweditaccess" @openmodal="openModal" @openreviewmodal="openReviewModal" ></ReviewSection>
            </div>
         </div>
         <ModalPopup :imagesrc="imageSrc" :reviews="reviews" :user="user" :product="product" :revieweditaccess="revieweditaccess" :currentuserreview="currentUserReview"></ModalPopup>
      </div>
   </div>
</template>

<script>
import ReviewSection from './ReviewSection';
import ModalPopup from './ModalPopup'

export default {
   name: "DescriptionSection",
   props:['product','tags','variants','user','reviews','totalrecords','totalpages','revieweditaccess'],
   data() {
      return {
         imageSrc:'',
         currentUserReview:'',
      }
   },
   mounted(){
         
   },
   created(){
   },  
   computed: {
      
   },
   methods: {
      openModal(src){
         this.imageSrc = src;
         $('#imageModal').modal('show');
      },
       openReviewModal(index){
         this.currentUserReview = this.reviews[index];
         $('#reviewModal').modal('show');
      },

     getVariants(data){
         let arrData = [];
         $.each(data, function(key, value) {
             arrData.push(value.options);
         });

         return arrData.join(', ');
     },
   },
   components: {
      ReviewSection,
      ModalPopup
   }
}
</script>

