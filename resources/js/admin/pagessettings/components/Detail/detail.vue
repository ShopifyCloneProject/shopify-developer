<template>
   <div>
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Detail section</h4>
                  <div class="heading-elements">
                     <ul class="list-inline mb-0">
                        <li class="movesection" @mouseover="setDraggable(true)" @mouseleave="setDraggable(false)"><a><i data-feather="move"></i></a></li>
                        <li>
                           <a data-action="collapse" class="rotate"><i data-feather="chevron-down"></i></a>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="card-content collapse">
                  <div class="card-body">
                     <div class="row" id="detailData">
                        <div class="col-sm-12">
                           <div class="demo-inline-spacing layout-sec">
                              <h5 class="mr-1">Visible on page:</h5>
                              <div class="custom-control custom-control-primary custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="detailDisplay" v-model.number="list.status"/>
                                 <label class="custom-control-label" for="detailDisplay"></label>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12">
                           <div class="demo-inline-spacing layout-sec">
                              <h5 class="mr-1">Layout:</h5>
                              <div :class="advanceSettings.detail_layout_position == 1 ? 'active' :''">
                                   <input type="radio" id="layout1" name="customRadio" class="custom-control-input d-none" value="1" v-model.number="advanceSettings.detail_layout_position" />
                                   <label class="" for="layout1"><img src="/assets/images/products/lefttoright.png" height="50" width="50" /></label>
                              </div>
                              <div :class="advanceSettings.detail_layout_position == 2 ? 'active' :''">
                                   <input type="radio" id="layout2" name="customRadio" class="custom-control-input  d-none" value="2" v-model.number="advanceSettings.detail_layout_position" />
                                   <label class="" for="layout2"><img src="/assets/images/products/righttoleft.png" height="50" width="50"/></label>
                              </div>
                           </div>
                        </div>
                        <div class="col-sm-12 d-none">
                           <div class="demo-inline-spacing layout-sec">
                              <h5 class="mr-1">Short Description Length:</h5>
                              <div :class="advanceSettings.detail_layout_position == 1 ? 'active' :''">
                                   <input class="form-control" type="number" id="charlimit" name="charlimit" v-model.number="advanceSettings.short_description_limit" />
                              </div>
                           </div>
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
   export default {
     props: ['list', 'advanceSettings'],
     name:'headersection',
     data() {
         return {
            
         }
     },
     components: {
     },
     created() {
     },
     mounted() {
      
     },
     methods: {
        setDraggable(status){
          this.$emit('setDraggable', status);
        },
        endDrag()
        {
           feather.replace();
           this.setWithParent();
        },
        setDraggableMenu(status){
          this.dragdisabled = status;
        },
     },
     computed: {
     },
   }
</script>
<style lang="scss" scoped>
.layout-sec{
   .active{
      label{
         border:1px solid;
      }
   }
}
</style>