import {Notifications} from "./notifications";
import {RateButtons} from "./ratebuttons";
import {HtmlRaw} from "./util";
import {Question} from "./question";
import {Card} from "./card";

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
            <div className="col-5 offset-2">
                {this.props.rated ?
                    <button onClick={() => this.props.nextItem()} className="btn btn-info btn-light btn-lg btn-block">
                        <span className="oi oi-arrow-thick-right"></span>
                    </button> : null}
            </div>
        )
    }

    renderAnswer() {
        return (
            <div className="answer w-100">
                {this.state.showResults && this.props.item.html.answer ?
                    <Card content={this.props.item.html.answer}/> : null}
            </div>
        )
    }

    renderItemCounter() {

        return (
            <h3 className="item-counter">
                <span className="badge badge-secondary">{this.props.count}</span>
            </h3>
        )
    }

    renderRateButtons() {
        return (
            <div className="rate-buttons">
                {this.state.showResults && !this.props.rated && this.props.item ?
                    <RateButtons handleRate={(i) => this.props.handleRate(i)}/> : null}
            </div>
        )
    }

    renderRatingIndicator() {
        return (
            <div className="rating-indicator">
                {this.state.showResults ? <HtmlRaw raw={this.props.item.html.rating_indicator}/> : null}
            </div>
        )
    }

    renderCategories() {

        return (
            <div>
                {this.props.item.html.categories && this.props.item.html.categories.length > 0 ?
                    <div className="row"><HtmlRaw raw={this.props.item.html.categories}/></div>
                    : null}
            </div>
        )
    }

    renderImages(){
        let images = this.props.item.data.images.map(
            (img, index) => <img className="img-thumbnail" key={index} src={img.url}/>
        );
        return (
            <div>
                {images}
            </div>
        )
    }

    render() {

        return (
            <div>
                <div className="row"> {this.renderItemCounter()}</div>
                {this.renderCategories()}
                <div className="row">
                    {this.renderImages()}
                    <Question
                        question={this.props.item.html.question}
                        questionMasked={this.props.item.html.question_masked}
                        questionSplit={this.props.item.html.question_split}
                        showResults={this.state.showResults}
                    />
                </div>
                <div className="row">{this.renderResultButton()}</div>
                <div className="row">{this.renderAnswer()}</div>
                <div className="row">{this.renderRatingIndicator()}</div>
                <div className="row">{this.renderRateButtons()}</div>
                {this.state.showResults ?
                    <div className="row">
                        <Notifications message={this.props.message}/>
                        {this.renderNextItemButton()}
                    </div>
                    : null
                }

            </div>
        )
    }
}