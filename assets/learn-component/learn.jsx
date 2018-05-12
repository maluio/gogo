// react get loaded in the twig template, because you don't get those insane file sizes from webpack during devlopment

//let React = require('react');
//let ReactDOM = require('react-dom');

require('./learn.scss');

import {Cards} from "./components/cards";

/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

class Learn extends React.Component {

    constructor() {
        super();
        this.state = {
            message: null,
            rated: false,
            items: [],
            item: {
                id: null,
                html: {
                    categories: null,
                    rating_indicator: null,
                    question: null,
                    question_masked: null,
                    question_split: null,
                    answer: null
                },
                data: {
                    images:[],
                    words: [],
                    phrases: [],
                    mainWord: null
                }
            }
        };
    }

    componentDidMount() {
        this.fetchDueItems();
    }

    fetchDueItems() {
        fetch(
            this.props.routing.generate('api_get_items') + '?due=true',
            {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            items: result,
                        }
                    });
                    this.handleNewItems();
                },
                (error) => {
                }
            )
    }

    handleRate(rating) {
        fetch(
            this.props.routing.generate('api_rate_item', {item: this.state.item.id}),
            {
                method: 'POST',
                body: JSON.stringify({
                    learn_rating: rating,
                }),
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            message: result,
                            rated: true
                        }
                    });
                },
                (error) => {
                }
            )
    }

    handleNewItems(){
        let item = null;
        let items = this.state.items;

        if (items.length > 0) {
            item = items.shift();
        }

        this.setState((prevState, props) => {
            return {
                message: null,
                rated: false,
                item: item,
                items: items
            }
        });
    }

    nextItem() {
        this.setState((prevState, props) => {
            return {
                item: null
            }
        });
        this.fetchDueItems();
    }

    renderCards() {
        return (
            <div>
                <Cards
                    item={this.state.item}
                    handleRate={(i) => this.handleRate(i)}
                    message={this.state.message}
                    rated={this.state.rated}
                    nextItem={()=> this.nextItem()}
                    count={this.state.items.length}
                />
            </div>
        )
    }

    render() {

        return (
            <div id="learn-view">
                {this.state.item ? <a href={this.props.routing.generate('item_edit', {id: this.state.item.id})}>edit</a> : null}
                {this.state.item ? this.renderCards() : <h1><span className="oi oi-check"></span></h1>}
            </div>
        )
    }
}

ReactDOM.render(<Learn routing={Routing}/>, document.getElementById("learn-jsx"));