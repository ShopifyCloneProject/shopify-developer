<template>
  <div id="input-sizing">
      <form id="frmAddEditLanguage">
          <div class="row">
            <div class="col-md-4 col-12">
                
                <ul class="nav nav-pills flex-column nav-left">
                      <!-- general -->
                    <li class="nav-item" 
                          v-for="(item, key) in list.language"
                          v-bind:key="key">
                        <a
                          class="nav-link languagehandle"
                          data-toggle="pill"
                          @click="setLanguageData(item.id,item.name,item.status)"
                          aria-expanded="true"
                          :id="'language_'+item.id"
                        >
                        <i data-feather='check-square'  class='font-medium-3 mr-1'></i>
                        <span class="font-weight-bold">{{item.name}}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body border-bottom">
                        <h4 class="mb-1 ">{{selectedlanguage.name}}</h4>
                        <p class="mb-1">{{selectedlanguage.status}}</p>
                    </div> 
                </div>
                <div class="form-group text-right mr-5" >
                    <button class="btn btn-primary waves-effect fixed-right-bottom" type="buttom" @click="saveSelectLanguage()">{{ lang.global.save }}</button>
                </div>
            </div>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['list','data'],
    name:'language',
    data() {
      return {
          name:'',
          selectedlanguage:{'name': null ,'status': null },
          selectionUserLanguageId: null,
      }
    },
    mounted(){
    },
    components: {
     
    },
    computed: {
    },
    created() {
          if(this.list.selected_language == null)
          {
            
            let selectedlanguage = this.list.language[0];
            this.setLanguageData(selectedlanguage.id,selectedlanguage.name,selectedlanguage.status);
          }
          else
          {
            let languageSelectionIndex = this.list.language.findIndex(language => language.id == this.list.selected_language)
            let selectedlanguage = this.list.language[languageSelectionIndex];
            this.setLanguageData(selectedlanguage.id,selectedlanguage.name,selectedlanguage.status);
          }
    },
    methods: {
        setLanguageData(language_id,languagename,lanuagestatus){
         
          this.selectedlanguage.name = languagename;
          this.selectedlanguage.status = lanuagestatus;
          this.selectionUserLanguageId = language_id;

          setTimeout(function(){
          $(".languagehandle").removeClass("active");
          $("#language_"+language_id).addClass("active");
          },500);
      },
      saveSelectLanguage(){

        openLoader();
        this.$store.dispatch("languageSettingsModule/AddEditLanguageSelection",{'language_id': this.selectionUserLanguageId})
          .then((res) => {
              if (res.response.status_code == 3020) {
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
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
</style>