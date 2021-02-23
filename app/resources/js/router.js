import VueRouter from "vue-router";
import Home from "./components/views/Home";
import Recipes from "./components/views/Recipes";
import Recipe from "./components/views/Recipe";

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
            path: "/recipe/:id",
            name: "recipe",
            component: Recipe
        },
    ],
    scrollBehavior() {
        document.getElementById('app').scrollIntoView();
    }
});
