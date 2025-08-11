import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import {post} from "./http.js";

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
        type: "success",

        close() {
            this.visible = false;
            clearInterval(this.interval);
            clearTimeout(this.timeout);
            this.interval = null;
            this.timeout = null;
            this.percent = 0;
        },

        show(message, type = "success") {
            this.visible = true;
            this.message = message;
            this.type = type;

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
                    const result = await post(this.product.addToCartUrl, {quantity});

                    this.$dispatch('cart-change', {count: result.count});
                    this.$dispatch("notify", {
                        message: "The item was added into the cart successfully",
                    });
                } catch (response) {
                    this.$dispatch("notify", {
                        message: response.message,
                        type: "error",
                    });
                }
            },

            removeItemFromCart: async function () {
                try {
                    const result = await post(this.product.removeUrl);

                    this.$dispatch('cart-change', {count: result.count});
                    this.$dispatch("notify", {
                        message: "The item was removed from cart successfully",
                    });
                    this.cartItems = this.cartItems.filter(p => p.id !== product.id);
                } catch (response) {
                    console.log(response);
                }
            },

            changeQuantity: async function () {
                try {
                    const result = await post(this.product.updateQuantityUrl, {quantity: product.quantity});

                    this.$dispatch('cart-change', {count: result.count});
                    this.$dispatch("notify", {
                        message: "The item quantity was updated successfully",
                    });
                } catch (response) {
                    // console.log(response);
                    this.$dispatch("notify", {
                        message: response.message,
                        type: "error",
                    });
                }
            }
        };
    });
});


Alpine.start();
