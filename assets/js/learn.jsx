let React = require('react');
let ReactDOM = require('react-dom');

/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

class Html extends React.Component {

    createMarkup() {
        return {__html: this.props.raw};
    }

    render() {

        return (
            <div dangerouslySetInnerHTML={this.createMarkup()}/>
        )
    }
}

class RateButtons extends React.Component {

    renderButton(i) {
        return (
            <button onClick={() => this.props.handleRate(i)} key={i} type="submit" className="btn btn-light btn-lg"
                    name="learn_rating" value={i}>{i}
            </button>
        )
    }

    renderUpdatingButton(i) {
        return (
            <button key={i} type="submit" className="btn btn-light btn-lg" disabled>
                {i}
            </button>
        )
    }

    render() {
        let buttons = [];

        for (let i = 0; i < 6; i++) {
            buttons.push(
                this.props.updating ? this.renderUpdatingButton(i) : this.renderButton(i)
            )
        }

        return (
            <div>
                {buttons}
            </div>
        )
    }
}

class Card extends React.Component {
    render() {
        return <div className="row">
            <div className="card card-outline-secondary mb-3">
                <div className="card-body">
                    <Html raw={this.props.content}/>
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
            updating: false,
            items: false,
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
        this.fetchDueItem();
    }

    toggleShowAnswer() {
        this.setState((prevState, props) => {
            return {showResults: !prevState.showResults}
        });
    };

    fetchDueItem() {
        fetch(
            this.props.routing.generate('api_items') + '?due=true',
            {
                method: 'GET',
                headers: {
                    'Authorization': this.props.pw
                },
            })
            .then(res => res.json())
            .then(
                (result) => {
                    console.log(result[0]);
                    this.state.updating = false;
                    this.setState((prevState, props) => {
                        return {
                            item: result[0],
                            showResults: false,
                            updating: false
                        }
                    });
                },
                (error) => {
                }
            )
    }

    handleRate(rating) {
        this.setState((prevState, props) => {
            return {
                updating: true
            }
        });
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
            .then(
                (result) => {
                    this.fetchDueItem();
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


    renderCards() {

        return (
            <div>
                <Html raw={this.state.item.html.categories}/>
                {this.state.showResults ? <Card content={this.state.item.html.question}/> :
                    <Card content={this.state.item.html.question_masked}/>}
                {this.renderResultButton()}
                {this.state.showResults ? <Html raw={this.state.item.html.rating_indicator}/> : null}
                {this.state.showResults && this.state.item.html.answer ?
                    <Card content={this.state.item.html.answer}/> : null}
                {this.state.showResults ?
                    <RateButtons updating={this.state.updating} handleRate={(i) => this.handleRate(i)}/> : null}
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