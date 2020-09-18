import Api from '../framework/api';
import AuthKinibind from "kiniauth/ts/framework/auth-kinibind";
import RequestParams from "kiniauth/ts/util/request-params";


export default class KcCart extends HTMLElement {

    // View
    private view;

    constructor() {
        super();
        this.bind();
    }


    private bind() {
        this.view = new AuthKinibind(
            this,
            {
                cart: {},
                cartItems: null,
                errors: RequestParams.get().errors ? JSON.parse(RequestParams.get().errors) : null,
                removeCartItem: (index) => {

                    const api = new Api();
                    api.removeCartItem(index).then(() => {
                        this.loadCart();
                    });
                }

            });

        this.loadCart();
    }

    private loadCart() {
        const api = new Api();
        api.getCart().then(cart => {
            this.view.model.cart = cart;
            this.view.model.cartItems = cart.items.length;
        });
    }

}
