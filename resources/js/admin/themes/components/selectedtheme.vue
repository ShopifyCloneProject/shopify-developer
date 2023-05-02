<template>
  <div id="input-sizing">
      <form id="frmAddEditThemes">
          <div class="row">
            <!-- Basic details start -->
                <div class="col-md-4">
                    <ul class="nav nav-pills flex-column nav-left">
                      <!-- general -->
                      <li class="nav-item" 
                          v-for="(item, key) in list.theme"
                          v-bind:key="key">
                        <a
                          class="nav-link themehandle"
                          data-toggle="pill"
                          @click="setThemeData(item.id,item.themeurl,item.name)"
                          aria-expanded="true"
                          :id="'theme_'+item.id"
                        >
                        <i data-feather="layers" class="font-medium-3 mr-1"></i>
                        <span class="font-weight-bold">{{item.name}}</span>
                        </a>
                      </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body border-bottom">
                          <h4 class="mb-1 ">{{selectedtheme.name}}</h4>
                          <p class="mb-1">{{selectedtheme.themeurl}}</p>
                          <div class="row justify-content-around">
                            <div class="col-md-4 col-6 theme-latest-img text-center" v-for="(item, key) in selectedtheme.images">
                              <a :href="item.imageurl" target="_new">
                                <img
                                  :src="item.imageurl"
                                  class="img-fluid rounded"
                                  alt="avatar img"
                                /> 
                              </a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right mr-5" >
                    <button class="btn btn-primary waves-effect fixed-right-bottom" type="buttom" @click="saveSelectTheme()">{{ lang.global.save }}</button>
                </div>
         
           
              <!-- Basic details end -->
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['list', 'data'],
    name:'themes',
    data() {
      return {
          name:[],
          client_id: CLIENT_ID,
          selectedtheme:{'name': null ,'themeurl': null ,'images' : []},
          selectionUserThemeId: null,
        }
    },
    created(){
      if(this.list.selected_theme == null)
      {
        
        let selectedtheme = this.list.theme[0];
        this.setThemeData(selectedtheme.id, selectedtheme.themeurl, selectedtheme.name);
      }
      else
      {
        let themeSelectionIndex = this.list.theme.findIndex(theme => theme.id == this.list.selected_theme)
        let selectedtheme = this.list.theme[themeSelectionIndex];
        this.setThemeData(selectedtheme.id, selectedtheme.themeurl, selectedtheme.name);
      }
    },
    mounted(){
    },
    components: {
     
    },
    computed: {
    
    },
    methods: {
      setThemeData(theme_id,themeurl,themename){
          let objImage =[];
          let  temeObjImage= {};
          for (const [key, value] of Object.entries( this.list.theme_media[theme_id])) {
                  temeObjImage = {
                              id: value.id,
                              imageurl: '/storage/'+CLIENT_ID+'/themes/'+theme_id+'/'+value.image
                              };
                objImage.push(temeObjImage);
          }
          this.selectedtheme.name = themename;
          this.selectedtheme.themeurl = themeurl;
          this.selectedtheme.images = objImage;
          this.selectionUserThemeId = theme_id;

          setTimeout(function(){
          $(".themehandle").removeClass("active");
          $("#theme_"+theme_id).addClass("active");
          },500);


      },
      saveSelectTheme(){
        openLoader();
        this.$store.dispatch("themeSelectionModule/AddEditThemeSelection",{'theme_id': this.selectionUserThemeId})
          .then((res) => {
              if (res.response.status_code == 3011) {
                  successModal(res.response.message);
              }
              else{
                errorModal(res.response.message);
              }
              closeLoader();
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
        });
      },
                
      }
    }
  
</script>

<style scoped>
.theme-latest-img img{
  width: 95%;
  height: 220px;
}
.theme-latest-img{
  transition: all 0.5s ease-in-out;
}
.theme-latest-img:hover{
  transform: scale(1.1);
}
</style>