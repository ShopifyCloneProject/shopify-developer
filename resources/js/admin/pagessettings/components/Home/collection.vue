<template>
  <div>
      <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Collection section</h4>
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
            <div class="row">
              <div class="col-sm-12">
                 <div class="demo-inline-spacing layout-sec mb-2">
                      <h5 class="mr-1">Visible on page:</h5>
                      <div class="custom-control custom-control-primary custom-checkbox">
                          <input type="checkbox" class="custom-control-input" id="collection" v-model="list.status" />
                          <label class="custom-control-label" for="collection"></label>
                      </div>
                  </div>
              </div>
              <div class="col-sm-12">
                  <h5>Select collections:</h5>
                  <multiselect
                      v-model="selectedCollections"
                      :options="collections"
                      :multiple="true"
                      :taggable="true"
                      placeholder="Type to search collection"
                      :custom-label="FilterTitleName"
                      track-by="id"
                      :close-on-select="false"
                      :clear-on-select="false"
                      @select=onSelect($event)
                      @remove=onRemove($event)
                      >
                      <template  slot="option" slot-scope="scope">
                        <div class="form-check form-check-inline"   @click.self="select(scope.option)">
                            <input class="form-check-input" type="checkbox" :id="'inlineCheckbox'+scope.option.id" v-model="scope.option.checked" @focus.prevent />
                            <label class="form-check-label"  :for="'inlineCheckbox'+scope.option.id">{{ scope.option.title }}</label>
                        </div>
                      </template>
                      <template slot="selection" slot-scope="{ values, search }">
                        <span class="multiselect__single" v-if="values.length">{{ values.length }} collection selected</span>
                      </template>
                 </multiselect>
                 <label>Selected collections will be display on home page. Default all collections will be display.</label>  
                <div class="form-group mt-2">
                    <span v-for="(collection, index) in selectedCollections">
                    <span class="badge badge-glow badge-primary mb-1 mr-1 pointer" @click.prevent="onRemove(collection)">{{ collection.title }} &#10006;</span>
                    </span>
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
    props: ['list', 'allCollections', 'selectedCollection'],
    name:'collection',
    data() {
      return {
        selectedCollections:[],
        collections:[]
      }
    },
    created() {
      this.collections = this.allCollections;
      this.selectedCollections = this.selectedCollection;
    },
    watch: {
       // whenever question changes, this function will run
      selectedCollections: function () {
        this.list.selectedCollections = this.selectedCollections;
      }
    },
    methods: {
      setDraggable(status){
        this.$emit('setDraggable', status);
      },
      FilterTitleName(option) {
        return `${option.title}`;
      },
      FilterName(option) {
        return `${option.name}`;
      },
      onSelect(options) {
        let index = this.collections.findIndex(item => item.id == options.id);
        this.collections[index].checked = true;
        
      },
      onRemove(options) {
        let tempSelectedCollections =   this.selectedCollections;
        let selectedCollectionsIndex = this.tempSelectedCollections.findIndex(item => item.id==options.id);
        this.tempSelectedCollections.splice(selectedCollectionsIndex,1); 
        this.selectedCollections = tempSelectedCollections;
        let listTagsIndex = this.collections.findIndex(item => item.id==options.id);
        this.collections[listTagsIndex].checked = false;
      }
    },
    computed: {
    },
  }
</script>

<style lang="scss" scoped>

</style>
