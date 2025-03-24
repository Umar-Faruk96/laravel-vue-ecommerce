import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import { post } from "./http.js";

Alpine.plugin(collapse)

window.Alpine = Alpine;

document.addEventListener("alpine:init", async () => {

    Alpine.data("toast", () => ({
        visible: false,
        delay: 5000,
        percent: 0,
        interval: null,
        timeout: null,
        message: null,

        close() {
            this.visible = false;
            clearInterval(this.interval);
        },

        show(message) {
            this.visible = true;
            this.message = message;

            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }

            if (this.timeout) {
                clearTimeout(this.timeout);
                this.timeout = null;
            }

            this.timeout = setTimeout(() => {
                this.visible = false;
                this.timeout = null;
            }, this.delay);

            const startDate = Date.now();
            const futureDate = Date.now() + this.delay;

            this.interval = setInterval(() => {
                const date = Date.now();

                this.percent = ((date - startDate) * 100) / (futureDate - startDate);

                if (this.percent >= 100) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            }, 30);
        },
    }));

    Alpine.data("productItem", (product) => {
        // console.log(product);

        return {
            product,

            addToCart: async function (quantity = 1) {
                try {
                    const result = await post(this.product.addToCartUrl, { quantity });

                    this.$dispatch('cart-change', { count: result.count });
                    this.$dispatch("notify", {
                        message: "The item was added into the cart",
                    });
                } catch (response) {
                    console.log(response);
                }
            },

            removeItemFromCart: async function () {
                try {
                    const result = await post(this.product.removeUrl);

                    this.$dispatch('cart-change', { count: result.count });
                    this.$dispatch("notify", {
                        message: "The item was removed from cart",
                    });
                    this.cartItems = this.cartItems.filter(p => p.id !== product.id);
                } catch (response) {
                    console.log(response);
                }
            },

            changeQuantity: async function () {
                try {
                    const result = await post(this.product.updateQuantityUrl, { quantity: product.quantity });

                    this.$dispatch('cart-change', { count: result.count });
                    this.$dispatch("notify", {
                        message: "The item quantity was updated",
                    });
                } catch (response) {
                    console.log(response);
                }
            }
        };
    });
});


Alpine.start();