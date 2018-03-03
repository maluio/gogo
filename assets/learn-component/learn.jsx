// react get loaded in the twig template, because you don't get those insane file sizes from webpack during devlopment

//let React = require('react');
//let ReactDOM = require('react-dom');

require('./learn.scss');

import {Notifications} from "./notifications";
import {RateButtons} from "./ratebuttons";
import {HtmlRaw} from "./util";

/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);


class Card extends React.Component {
    render() {
        return <div className="row">
            <div className="card card-outline-secondary mb-3">
                <div className="card-body">
                    <HtmlRaw raw={this.props.content}/>
                </div>
            </div>
        </div>;
    }
}

class Learn extends React.Component {

    constructor() {
        super();
        this.state = {
            showResults: false,
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
                    answer: null
                }
            }
        };
    }

    componentDidMount() {
        this.fetchDueItems();
    }

    toggleShowAnswer() {
        this.setState((prevState, props) => {
            return {showResults: !prevState.showResults}
        });
    };

    fetchDueItems() {
        fetch(
            this.props.routing.generate('api_get_items') + '?due=true',
            {
                method: 'GET',
                headers: {
                    'Authorization': this.props.pw
                },
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            items: result,
                            showResults: false,
                        }
                    });
                    this.nextItem();
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
                headers: {
                    'Authorization': this.props.pw
                },
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

    renderResultButton() {
        return (
            <button onClick={() => this.toggleShowAnswer()} className="btn btn-light js-show btn-lg btn-block">
                <span className="oi oi-elevator"></span>
            </button>
        )
    }

    renderNextItemButton() {
        return (
            <span>
            {this.state.rated ?
                <button onClick={() => this.fetchDueItems()} className="btn btn-light btn-lg">
                    <span className="oi oi-arrow-thick-right"></span>
                </button> : null}
            </span>
        )
    }

    renderItemCounter(){

        let count = this.state.items.length + 1;

        return(
            <h3>
                <span className="badge badge-secondary">{count}</span>
            </h3>
        )
    }

    nextItem() {
        let item = null;
        let items = this.state.items;

        if(items.length > 0){
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


    renderCards() {

        return (
            <div>
                {this.renderItemCounter()}
                <HtmlRaw raw={this.state.item.html.categories}/>
                {this.state.showResults ? <Card content={this.state.item.html.question}/> :
                    <Card content={this.state.item.html.question_masked}/>}
                {this.renderResultButton()}
                {this.state.showResults ? <HtmlRaw raw={this.state.item.html.rating_indicator}/> : null}
                {this.state.showResults && this.state.item.html.answer ?
                    <Card content={this.state.item.html.answer}/> : null}
                {this.state.showResults && !this.state.rated && this.state.item ?
                    <RateButtons handleRate={(i) => this.handleRate(i)}/> : null}
                <div className="row">
                    {this.renderNextItemButton()}
                    <Notifications message={this.state.message}/>
                </div>
            </div>
        )
    }

    render() {

        return (
            <div id="learn-view">
                {this.state.item ? this.renderCards() : <h1><span className="oi oi-check"></span></h1>}
            </div>
        )
    }
}

ReactDOM.render(<Learn pw={GoGo.basicAuthValue} routing={Routing}/>, document.getElementById("learn-jsx"));