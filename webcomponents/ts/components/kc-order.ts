import Api from '../framework/api';
import RequestParams from 'kiniauth/ts/util/request-params';
import AuthKinibind from "kiniauth/ts/framework/auth-kinibind";
import Session from "kiniauth/ts/framework/session";


export default class KcOrder extends HTMLElement {

    private view;

    constructor() {
        super();

        this.bind();
    }


    private bind() {

        this.view = new AuthKinibind(
            this,
            {
                order: {},
                currency: ''
            });

        const api = new Api();

        const orderId = RequestParams.get().orderId;

        if (orderId) {
            api.getOrder(orderId).then(order => {
                this.updateView(order);
            });
        } else {
            Session.getSessionData(true).then(session => {
                this.updateView(session.lastSessionOrder);
            });
        }

    }

    private updateView(order) {
        this.view.model.order = order;
        switch (order.currency) {
            case 'USD':
                this.view.model.currency = '$';
                break;
            case 'GBP':
                this.view.model.currency = '£';
                break;
            case 'EUR':
                this.view.model.currency = '€';
                break;
        }
    }

}
