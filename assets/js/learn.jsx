let React = require('react');
let ReactDOM = require('react-dom');

/* routes */
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

class RateButtons extends React.Component {

    render() {
        let buttons = [];

        for (let i = 0; i < 6; i++) {
            buttons.push(
                <button onClick={() => this.props.handleRate(i)} key={i} type="submit" className="btn btn-light btn-lg"
                        name="learn_rating" value={i}>{i}</button>
            )
        }

        return (
            <div>{buttons}</div>
        )
    }
}

class Card extends React.Component {
    render() {
        return <div className="row">
            <div className="card card-outline-secondary mb-3">
                <div className="card-body">
                    <p>{this.props.content}</p>
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
            item: {
                question: '',
                answer: '',
                id: null
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
            this.props.routing.generate('learn_react_due'),
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
                            item: result.item,
                            showResults: false
                        }
                    });
                },
                (error) => {
                }
            )
    }

    handleRate(rating) {
        fetch(
            this.props.routing.generate('learn_rate', {item: this.state.item.id}),
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

    renderResultButton(){
        return (
            <button onClick={() => this.toggleShowAnswer()} className="btn btn-light js-show btn-lg btn-block">
                <span className="oi oi-elevator"></span>
            </button>
        )
    }

    renderCards() {

        return (
            <div>
                <Card content={this.state.item.question}/>
                {this.renderResultButton()}
                {this.state.showResults ? <Card content={this.state.item.answer}/> : null}
                {this.state.showResults ? <RateButtons handleRate={(i) => this.handleRate(i)}/> : null}
            </div>
        )
    }

    render() {

        return (
            <div id="learn-view">
                {this.state.item ? this.renderCards() :  <h1><span className="oi oi-check"></span></h1>}
            </div>
        )
    }
}

ReactDOM.render(<Learn pw={GoGo.basicAuthValue} routing={Routing}/>, document.getElementById("learn-jsx"));