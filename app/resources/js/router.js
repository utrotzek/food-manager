import VueRouter from "vue-router";
import Home from "./components/views/Home";
import Recipes from "./components/views/Recipes";
import RecipesForm from "./components/views/RecipeForm";

export default new VueRouter({
    mode: "history",
    linkActiveClass: "active",
    linkExactActiveClass: "exact-active",
    routes: [
        {
            path: "/",
            name: "home",
            component: Home
        },
        {
            path: "/recipes",
            name: "recipes",
            component: Recipes
        },
        {
            path: "/recipes-form/:id",
            name: "recipe-form",
            component: RecipesForm
        },
    ],
    scrollBehavior() {
        document.getElementById('app').scrollIntoView();
    }
});
