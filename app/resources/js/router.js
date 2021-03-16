import VueRouter from "vue-router";
import Home from "./components/views/Home";
import Recipes from "./components/views/Recipes";
import Recipe from "./components/views/Recipe";
import RecipeForm from "./components/views/RecipeForm";

const router = new VueRouter({
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
        {
            path: "/recipe-form/:id?",
            name: "recipe-form",
            component: RecipeForm
        },
    ],
    scrollBehavior (to, from, savedPosition) {
        let position = null;
        if (savedPosition) {
            position = savedPosition;
        } else {
            position = { x: 0, y: 0 }
        }
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(position)
            }, 400)
        })
    },
});

export default router;
