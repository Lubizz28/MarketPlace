/**
 * MedinaStyle Unified Analytics & E-Commerce Event Bus
 * Supports Google Analytics 4 (GA4) dataLayer, Meta Pixel standard events, and Custom DOM events.
 */

window.dataLayer = window.dataLayer || [];

export const MedinaAnalytics = {
    debug: false,

    /**
     * Push generic event to dataLayer and dispatch DOM CustomEvent
     */
    track(eventName, params = {}) {
        const payload = {
            event: eventName,
            timestamp: new Date().toISOString(),
            ...params,
        };

        // Push to Google Tag Manager / GA4 DataLayer
        window.dataLayer.push(payload);

        // Dispatch DOM event for custom listeners
        window.dispatchEvent(
            new CustomEvent('medina:analytics', {
                detail: payload,
            })
        );

        if (this.debug) {
            console.log(`[MedinaAnalytics] Event: ${eventName}`, payload);
        }
    },

    /**
     * E-Commerce: View Item List (Catalog / Category / Home)
     */
    viewItemList(items, listName = 'Product Catalog') {
        this.track('view_item_list', {
            item_list_name: listName,
            items: items.map((item, index) => ({
                item_id: item.id || item.sku,
                item_name: item.name,
                price: Number(item.price || 0),
                item_category: item.category || 'Busana Muslim',
                item_brand: item.brand || 'MedinaStyle',
                index: index + 1,
            })),
        });
    },

    /**
     * E-Commerce: View Item Detail
     */
    viewItem(item) {
        this.track('view_item', {
            currency: 'IDR',
            value: Number(item.price || 0),
            items: [
                {
                    item_id: item.id || item.sku,
                    item_name: item.name,
                    price: Number(item.price || 0),
                    item_category: item.category || 'Busana Muslim',
                    item_brand: item.brand || 'MedinaStyle',
                    item_variant: item.variant || null,
                },
            ],
        });
    },

    /**
     * E-Commerce: Add To Cart
     */
    addToCart(item, quantity = 1) {
        this.track('add_to_cart', {
            currency: 'IDR',
            value: Number(item.price || 0) * quantity,
            items: [
                {
                    item_id: item.id || item.sku,
                    item_name: item.name,
                    price: Number(item.price || 0),
                    item_variant: item.variant || null,
                    quantity: quantity,
                },
            ],
        });
    },

    /**
     * E-Commerce: Remove From Cart
     */
    removeFromCart(item, quantity = 1) {
        this.track('remove_from_cart', {
            currency: 'IDR',
            value: Number(item.price || 0) * quantity,
            items: [
                {
                    item_id: item.id || item.sku,
                    item_name: item.name,
                    price: Number(item.price || 0),
                    quantity: quantity,
                },
            ],
        });
    },

    /**
     * E-Commerce: Begin Checkout
     */
    beginCheckout(items, totalValue, coupon = null) {
        this.track('begin_checkout', {
            currency: 'IDR',
            value: Number(totalValue || 0),
            coupon: coupon,
            items: items.map((item) => ({
                item_id: item.id || item.sku,
                item_name: item.name,
                price: Number(item.price || 0),
                item_variant: item.variant || null,
                quantity: item.quantity || 1,
            })),
        });
    },

    /**
     * E-Commerce: Apply Coupon / Promo Code
     */
    applyCoupon(couponCode, discountAmount) {
        this.track('apply_coupon', {
            coupon: couponCode,
            discount_value: Number(discountAmount || 0),
        });
    },

    /**
     * E-Commerce: Purchase Completed
     */
    purchase(order) {
        this.track('purchase', {
            transaction_id: order.order_number,
            value: Number(order.grand_total || 0),
            tax: 0,
            shipping: Number(order.shipping_cost || 0),
            currency: 'IDR',
            coupon: order.coupon_code || null,
            items: (order.items || []).map((item) => ({
                item_id: item.sku || item.product_id,
                item_name: item.product_name,
                item_variant: item.variant_name,
                price: Number(item.price || 0),
                quantity: item.quantity,
            })),
        });
    },

    /**
     * Growth & Viral: Share Referral Link
     */
    shareReferral(code, targetUrl = null, platform = 'clipboard') {
        this.track('share_referral', {
            referral_code: code,
            target_url: targetUrl,
            method: platform,
        });
    },

    /**
     * Marketing: Select Promotion / Flash Sale Click
     */
    selectPromotion(promoId, promoName, slot = 'banner') {
        this.track('select_promotion', {
            creative_name: promoName,
            creative_slot: slot,
            promotion_id: promoId,
            promotion_name: promoName,
        });
    },
};

// Global Exposure
window.MedinaAnalytics = MedinaAnalytics;

// Global helper function for inline Blade usage
window.trackAnalytics = function(eventName, payload) {
    MedinaAnalytics.track(eventName, payload);
};

// Auto-bind declarative clicks with [data-analytics-event]
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-analytics-event]');
        if (trigger) {
            const eventName = trigger.getAttribute('data-analytics-event');
            let payload = {};
            try {
                const rawPayload = trigger.getAttribute('data-analytics-payload');
                if (rawPayload) {
                    payload = JSON.parse(rawPayload);
                }
            } catch (err) {
                console.warn('[MedinaAnalytics] Invalid JSON in data-analytics-payload', err);
            }
            MedinaAnalytics.track(eventName, payload);
        }
    });
});
