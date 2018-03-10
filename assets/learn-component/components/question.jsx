import {Card} from "./card";

class Word extends React.Component {
    constructor() {
        super();
        this.state = {
            value: ''
        }
    }

    handleChange(event) {
        let currentInput = event.target.value;
        this.handleNewValue(currentInput);
    }

    handleNewValue(value) {
        this.setState((prevState, props) => {
            return {
                value: value,
            }
        });
    }

    hintLetter() {
        let word = this.props.word;
        let value = this.state.value;

        if (0 !== word.indexOf(value)) {
            value = '';
        }

        value = value + word.charAt(value.length);

        this.handleNewValue(value);
    }

    getClass(){
        let lowerCaseValue = this.state.value.toLowerCase();
        let lowerCaseWord = this.props.word.toLowerCase();

        if(lowerCaseValue === lowerCaseWord) {
            return 'word-found';
        }

        if(0 !== lowerCaseWord.indexOf(lowerCaseValue)){
            return 'wrong-letter';
        }

        return '';
    }

    render() {
        return (
            <span className="word-check">
                    <input
                        value={this.state.value}
                        onChange={(e) => this.handleChange(e)}
                        type="text"
                        className={this.getClass()}
                    />
                    <button onClick={() => this.hintLetter()}><span className="oi oi-question-mark"></span></button>
            </span>
        )
    }
}

export class Question extends React.Component {

    renderMaskedQuestion() {

        return (
            <div>
                {this.props.questionSplit ? this.props.questionSplit
                    .filter(function (part) {
                        return part.hidden;
                    })
                    .map(function (part, i) {
                            return (
                                <Word key={i} word={part.string}/>
                            )
                        }
                    ) : null}
            </div>
        )
    }

    render() {

        return (
            <div className="w-100">
                {this.props.showResults ? <Card content={this.props.question}/> :
                    <Card content={this.props.questionMasked}/>}
                {this.renderMaskedQuestion()}
            </div>
        )
    }
}