const app = new Vue({
  el: '#app',
  data: {
    errorMsg: '',
    successMsg: '',
    showAddRecipeModal: false,
    showAddIngredientModal: false,
    showEditRecipeModal: false,
    showDeleteRecipeModal: false,
    ingredients: [],
    ingredientsList: [],
    recipes: [],
    newIngredient: { nameIngredient: '' },
    counter: 0,
    newIngredientsList: [
      { id: 'ingredient0', ingredient: '', order: '', quantity: '' }
    ],
    newRecipe: { nameRecipe: '' },
    currentIngredient: {},
    currentRecipe: {}
  },

  mounted: function () {
    this.getAllRecipes()
    this.getAllIngredients()
  },

  methods: {
    addInputIngredient() {
      this.newIngredientsList.push({
        id: `ingredient${++this.counter}`,
        ingredient: '',
        order: '',
        quantity: ''
      })
    },

    getAllIngredients() {
      axios
        .get('http://localhost/desafio_stw/process.php?action=readIngredient')
        .then(function (response) {
          if (response.data.error) {
            app.errorMsg = response.data.message
          } else {
            app.ingredients = response.data.ingredients
          }
        })
    },
    getAllRecipes() {
      axios
        .get('http://localhost/desafio_stw/process.php?action=readRecipes')
        .then(function (response) {
          if (response.data.error) {
            app.errorMsg = response.data.message
          } else {
            app.recipes = response.data.recipes
          }
        })
    },

    addIngredient() {
      let formData = app.formData(app.newIngredient)
      axios
        .post(
          'http://localhost/desafio_stw/process.php?action=createIngredient',
          formData
        )
        .then(function (response) {
          app.newIngredient = { nameIngredient: '' }
          if (response.data.error) {
            app.errorMsg = response.data.message
          } else {
            app.successMsg = response.data.message
            app.getAllIngredients()
          }
        })
    },

    addRecipe() {
      let formData = app.formData(app.newRecipe)
      let formDataIngredients = app.formData(app.newIngredientsList)
      formData = {
        ...formData,
        ...formDataIngredients
      }
      axios
        .post(
          'http://localhost/desafio_stw/process.php?action=createRecipe',
          formData
        )
        .then(function (response) {
          app.newRecipe = { nameRecipe: '' }
          if (response.data.error) {
            app.errorMsg = response.data.message
          } else {
            app.successMsg = response.data.message
            app.getAllRecipes()
          }
        })
    },

    updateRecipe() {
      let formData = app.formData(app.newRecipe)
      axios
        .post(
          'http://localhost/desafio_stw/process.php?action=updateRecipe',
          formData
        )
        .then(function (response) {
          app.currentRecipe = {}
          if (response.data.error) {
            app.errorMsg = response.data.message
          } else {
            app.successMsg = response.data.message
            app.getAllRecipes()
          }
        })
    },

    formData(obj) {
      let data = new FormData()
      for (let i in obj) {
        data.append(i, obj[i])
      }
      return data
    },
    selectRecipe(recipe) {
      app.currentRecipe = recipe
    }
  }
})
