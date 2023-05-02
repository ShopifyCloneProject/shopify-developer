<template>
   <div>
         <!-- Write Review Modal Start -->
      <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewLabel" aria-hidden="true">
         <div class="modal-dialog  modal-dialog-scrollable" role="document">
            <div class="modal-content">
               <ValidationObserver ref="reviewform" v-slot="{ handleSubmit, invalid, reset }">
                  <form class="reviewform" @submit.prevent="handleSubmit(saveReview())">
                     <div class="modal-header">
                        <h5 class="modal-title" id="reviewLabel" v-if="revieweditaccess == 1">{{ lang.global.write_review }}</h5>
                        <h5 class="modal-title" id="reviewLabel" v-else>{{ lang.global.edit_review }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                     </div>
                     <div class="modal-body">
                        <div class="row my-2">
                           <div class="col-18 mb-2">
                              <label class="label">{{ lang.global.productdetail.descriptionsection.star_rate }}</label>
                              <div class="form-group">
                                 <star-rating v-bind:increment="1"
                                 v-bind:max-rating="5"
                                 inactive-color="#dadada"
                                 active-color="#efce4a"
                                 v-bind:star-size="30"
                                 v-model="currentuserreview.star_rating">
                                 </star-rating>
                              </div>
                           </div>
                           <div class="col-18 mb-2">
                              <label class="label">{{ lang.global.productdetail.descriptionsection.title }}</label>
                              <div class="form-group">
                                 <input type="text" class="form-control input" v-model="currentuserreview.title">
                              </div>
                           </div>
                           <div class="col-18 mb-2">
                              <label class="label">{{ lang.global.productdetail.descriptionsection.review_description }}</label>
                              <div class="form-group green-border-focus">
                                 <textarea class="form-control" id="reviewDescription" rows="3" v-model="currentuserreview.description">
                                 </textarea>
                              </div>
                           </div>
                           <div class="col-18 mb-2">
                              <div class="card" id="mediaDataId">
                                 <div class="header">
                                    <h4 class="card-title">{{ lang.global.media }}</h4>
                                 </div>
                                 <div class="card-body">
                                    <section>
                                       <button v-if="showDeleteBtn" @click.prevent="deleteMedia" type="button" class="btn waves-effect pull-right">{{ lang.global.deleteselectmedia }}</button>
                                       <div class="draggable-outer-line" v-if="currentuserreview.media.length == 0 "> 
                                          <div class="row no-item"  @click.prevent="callFileData">
                                             <div class="col-md-18 icon">
                                                <img width="40" src="data:image/svg+xml,%3csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M20 10a10 10 0 11-20 0 10 10 0 0120 0zM5.3 8.3l4-4a1 1 0 011.4 0l4 4a1 1 0 01-1.4 1.4L11 7.4V15a1 1 0 11-2 0V7.4L6.7 9.7a1 1 0 01-1.4-1.4z' fill='%235C5F62'/%3e%3c/svg%3e"  alt="" />
                                             </div>
                                             <div class="col-md-18 text">
                                                <div class="add-file"> {{ lang.global.add_media }} </div>
                                             </div> 
                                          </div>   
                                       </div> 
                                       <div class="DropZone__Container hasitem" v-else>
                                          <div class="dropzone-first">
                                             <draggable tag="div" v-model="currentuserreview.media" class="dropzone-second-div" draggable=".item">
                                                <div v-for="(imagedata,index) in currentuserreview.media" class="item">
                                                   <button class="btnImage" type="button" @mouseover="mouseOverDiv(index)" @mouseleave="mouseLeaveDiv(index)">
                                                      <div class="checkbox-outer" v-show="imagedata.displaycheckbox">
                                                         <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" :id="'deletecheckbox'+imagedata.id" v-model="imagedata.checked" @change="checkAnySelect">
                                                            <label class="form-check-label" :for="'deletecheckbox'+imagedata.id"></label>
                                                         </div>
                                                      </div>
                                                      <div class="image-outer" :class="(imagedata.displaycheckbox)?'opacitityDown':''">
                                                         <img class="main-image" :src="imagedata.src"  alt="">
                                                      </div>
                                                   </button>
                                                </div>
                                                <div class="add-media-outer">
                                                   <button class="add-image" type="button" @click.prevent="callFileData">
                                                      <span class="add-image-link">{{ lang.global.add_media }}</span>
                                                   </button>
                                                </div>
                                             </draggable>
                                          </div>
                                       </div>
                                    </section>

                                    <template>
                                       <form ref="mediaform">
                                          <input type="file" name="media[]" multiple="" accept="image/jpeg, image/png" id="media" class="opacity-0" @change="passImageData($event)">
                                       </form>
                                    </template>
                                    <!-- multi file upload ends -->
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">{{ lang.global.cancel }}</button>
                        <button type="submit" class="btn btn-primary">{{ lang.global.save }}</button>
                     </div>
                  </form>
               </ValidationObserver>
            </div>
         </div>
      </div>
      <!-- Write Review Modal End -->

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
                  <div>
                     <img :src="imagesrc">
                  </div>
               </div>
            </div>
         </div>
      </div> 
      <!-- image modal end  -->
   </div>
</template>

<script>
   import { mapState } from 'vuex';
   import draggable from 'vuedraggable'

   export default {
      name: "ModalPopup",
      props:['product', 'imagesrc','revieweditaccess','reviews' , 'user','currentuserreview'],
      data() {
         return {
               star_rating:0,
               title: '',
               description: '',
               media:[],
               showDeleteBtn: false,
           
         }
      },
      mounted(){
         this.media = this.currentuserreview.media;
      },
      created(){

      },  
      computed: {
         ...mapState(['globalStore']),
         noImage(){
            return this.globalStore.no_image;
         }
         

      },
      methods: {
         closeModal(){
            $('#reviewModal').modal('hide');
         },
         callFileData(){
            $("#media").trigger('click');
         },
         mouseOverDiv(index){
            this.media[index].displaycheckbox = true;
         },
         mouseLeaveDiv(index){
            this.media[index].displaycheckbox = false;
            this.checkAnySelect();
         },
         appendMedia(base64image){
            this.media.push({id: Math.floor((Math.random() * -10000000)),  imageurl: base64image, checked: false, displaycheckbox: false, media: [] });
         },
         checkAnySelect(){
            let funCheckDisplayCheckbox = false;
            for (const [key, value] of Object.entries(this.media)) {
               if(value.checked)
               {
                  funCheckDisplayCheckbox = true;
                  this.showDeleteBtn = true;
                  break;
               }
            }

            if(funCheckDisplayCheckbox)
            {
               for (const [key, value] of Object.entries(this.media)) {
                  value.displaycheckbox = true;
               }
            }
            else
            {
               for (const [key, value] of Object.entries(this.media)) {
                  value.displaycheckbox = false;
               }
               this.showDeleteBtn = false;
            }
         },
         deleteMedia(){
            for (const [key, value] of Object.entries(this.media)) {
               if(value.checked) 
               {
                  this.deleteMediaIndex();
               }
            }
            this.showDeleteBtn = false;
            this.checkAnySelect();
         },
         deleteMediaIndex(){
            for (const [key, value] of Object.entries(this.media)) {
               if(value.checked)
               {
                  this.media.splice(key, 1);
                  this.deleteMedia();
               }
            }
         },
         passImageData(event){
            blockSection($("#mediaDataId"));
            let self = this;
            for (const [key, value] of Object.entries(event.target.files)) {
               const  reader = new FileReader();
               reader.readAsDataURL(value); 
               reader.onload  = function() {
                  self.appendMedia(reader.result);
               }
            }
            self.$refs.mediaform.reset();
            unblockSection($("#mediaDataId"));
         },
         setAltImg(event){
            event.target.src = this.noImage;
         },
         saveReview(){
            let section = $('.modal-dialog');
            blockSection(section);
            const  payload = {
               product_id:this.product.id,
               star_rating: this.currentuserreview.star_rating,
               title: this.currentuserreview.title,
               description: this.currentuserreview.description,
               media : this.currentuserreview.media
            };
            this.$store.dispatch("globalStore/addReview", payload)
            .then((res) => {
               if (res.response.status_code == 3124) {
                  this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });
                  location.reload();
               }
               this.closeModal();
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               this.$toast.open({
                  message: err,
                  type: "error",
               });
            });
         },
      },
      components: {
      }
   }
</script>
<style lang="scss" scoped>

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
   .form-control{
      background-color:#fff;
      border:1px solid #e1dfdf;
   }
   .green-border-focus .form-control:focus {
      border: 1px solid #80bdff;
      box-shadow:0px 0px 0px 2px rgb(0,123,255,0.25);

   }
   .cart-table-prd-info{
      flex-basis: 40%;
      .minicart-qty{
         position: relative;
         color: #fff;
         border-color: #17c6aa;
         background-color: #17c6aa;
         padding: 13px;
         font-size: 14px;
      }
   }
   .draggable-outer-line{
      padding:50px;
      width: 100%;
      border: 2px dashed #9f9f9f;
      border-radius: 15px;
      &:hover{
         background-color: #e3e2e2;
         cursor: pointer;
      }
      .no-item{
         .icon{
            text-align: center;
            margin-bottom: 10px;
         }
         .text{
            text-align: center;
            .add-file{
               display: inline-block;
               padding: 10px 15px;
               border: 2px solid #c1c1c1;
               border-radius: 5px;
               font-weight: 600;
            }
         }
      }

   }

   .DropZone__Container{
      min-height: 200px;
      position: relative;
      flex: 1 1;
      .dropzone-first {
         position: relative;
         height: 100%;
      }
      .dropzone-second-div {
         display: grid;
         grid-gap: .8rem;
         -webkit-user-select: none;
         user-select: none;
         .btnImage {
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: .1rem solid #c9cccf;
            background: #ffffff;
            border-radius: 6px;
            padding: 0;
            margin: 0;
            .checkbox-outer{
               position: absolute;
               z-index: 99;
               top: 2%;
               left: 5%;
            }
            .image-outer{
               width: 100%;
               display: flex;
               overflow: hidden;
               flex-direction: column;
               z-index: 5;
               align-items: center;
               justify-content: center;
               border-radius: 5px;
               transform: translateZ(0);
               &:after{
                  content: "";
                  display: block;
                  width: 100%;
                  padding-bottom: 100%;
               }
               img {
                  position: absolute;
                  z-index: 1;
                  max-width: 100%;
                  max-height: 100%;
               }
            }   
         }
      }
   }
   @media (min-width: 0px) and (max-width: 991.98px)
   {
      .dropzone-second-div{
         grid-template-columns: repeat(3,1fr);
      }
   }

   @media (min-width: 992px)
   {
      .dropzone-second-div{
         grid-template-columns: repeat(4,1fr);
      }
   }
   .pull-right{
      display:block;
      margin-bottom:20px;
      margin-left: auto;
      background-color : #dc3545;
   }

   .add-image{
      height: 100%;
      border: 0.2rem dashed #c9cccf !important;
      min-height: 150px;
      .image-outer-media{
         position: absolute;
         width: 100%;
         height: 100%;
         display: flex;
         align-items: center;
         flex-direction: column;
         text-align: center;
         padding: .8rem;
         cursor: pointer;
         outline: none;
         background: none;
         border: none;
         .add-image-link{
            margin-top: 40%;
         }
      }
   }
   .opacitityDown{
      background-color: #969393;
      opacity: 0.6;
   }

   .add-media-outer{
      position: relative;
      width: 100%;
      height: 0;
      padding-bottom: calc(100% - .4rem);
      border-radius: 3px;
      .add-image{
         position: absolute;
         width: 100%;
         height: 100%;
         display: flex;
         align-items: center;
         flex-direction: column;
         text-align: center;
         padding: .8rem;
         cursor: pointer;
         outline: none;
         background: none;
         .add-image-link{
            margin-top: 40%;
         }
      }
   }
   .hidden {
      display: none;
   }
   .modal {
      padding-top: 0px;
      overflow-y: scroll;
   }
   .hide{
      display:none;
   }
   .close{
      right: 0px;
      top: 0px;
   }
</style>