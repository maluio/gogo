/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

export class Http {
    static fetch(url){
        return fetch(
            url,
            {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    return result;
                },
                (error) => {
                    console.log(error);
                }
            )
    }

    static fetchImages(term){
        return this.fetch(Routing.generate('search_images', {term: term}));
    }

    static fetchTranslation(term){
        return this.fetch(Routing.generate('translate', {term: term}));
    }
}