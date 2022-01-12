import VueRouter from "vue-router";
import Home from "./components/views/Home";
import Recipes from "./components/views/Recipes";
import Recipe from "./components/views/Recipe";
import RecipeForm from "./components/views/RecipeForm";
import MealPlan from "./components/views/MealPlan";
import ShoppingListPrint from "./components/views/ShoppingListPrint";
import Auth from "./components/views/Auth";
import Settings from "./components/views/Settings";
import Calendar from "./components/settings/Calendar";
import Goods from "./components/settings/Goods";

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
            path: "/recipe/:id/:cooking?/:portion?",
            name: "recipe",
            component: Recipe
        },
        {
            path: "/recipe-form/:id?",
            name: "recipe-form",
            component: RecipeForm
        },
        {
            path: "/meal-plan",
            name: "meal-plan",
            component: MealPlan
        },
        {
            path: "/print-shopping-list/:id",
            name: "print-shoppling-list",
            component: ShoppingListPrint
        },
        {
            path: "/settings",
            name: "settings",
            component: Settings,
            children: [
                {
                    path: "calendar",
                    name: "settings-calendar",
                    component: Calendar,
                    meta: {
                        settingModule: "calendar"
                    }
                },
                {
                    path: "goods",
                    name: "settings-goods",
                    component: Goods,
                    meta: {
                        settingModule: "goods"
                    }
                }
            ]
        },
        {
            path: "/calendar/auth",
            name: "calendar-auth",
            component: Auth
        }
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
