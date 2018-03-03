import {Notifications} from "./notifications";
import {RateButtons} from "./ratebuttons";
import {HtmlRaw} from "./util";

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

export class Cards extends React.Component {
    constructor() {
        super();
        this.state = {
            showResults: false,
        };
    }

    toggleShowAnswer() {
        this.setState((prevState, props) => {
            return {showResults: !prevState.showResults}
        });
    };

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
            {this.props.rated ?
                <button onClick={() => this.props.nextItem()} className="btn btn-light btn-lg">
                    <span className="oi oi-arrow-thick-right"></span>
                </button> : null}
            </span>
        )
    }

    renderQuestion(){
        return(
            <div className="question">
            {this.state.showResults ? <Card content={this.props.item.html.question}/> :
                <Card content={this.props.item.html.question_masked}/>}
            </div>
        )
    }

    renderAnswer(){
        return(
            <div className="answer">
                {this.state.showResults && this.props.item.html.answer ?
                    <Card content={this.props.item.html.answer}/> : null}
            </div>
        )
    }

    renderItemCounter(){

        return(
            <h3 className="item-counter">
                <span className="badge badge-secondary">{this.props.count}</span>
            </h3>
        )
    }

    renderRateButtons(){
        return (
            <div className="rate-buttons">
                {this.state.showResults && !this.props.rated && this.props.item ?
                    <RateButtons handleRate={(i) => this.props.handleRate(i)}/> : null}
            </div>
        )
    }

    renderRatingIndicator(){
        return (
            <div className="rating-indicator">
                {this.state.showResults ? <HtmlRaw raw={this.props.item.html.rating_indicator}/> : null}
            </div>
        )
    }

    render() {

        return (
            <div>
                {this.renderItemCounter()}
                <HtmlRaw raw={this.props.item.html.categories}/>
                {this.renderQuestion()}
                {this.renderResultButton()}
                {this.renderAnswer()}
                {this.renderRatingIndicator()}
                {this.renderRateButtons()}
                <div className="row">
                    {this.renderNextItemButton()}
                    <Notifications message={this.props.message}/>
                </div>
            </div>
        )
    }
}