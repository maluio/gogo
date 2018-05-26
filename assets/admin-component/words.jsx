import {Http} from "./http";

const {
    Form,
    FormGroup,
    Input,
    Label,
    Button,
    ListGroup,
    ListGroupItem
} = Reactstrap;

export class Words extends React.Component {

    constructor(){
        super();
        this.state = {
            translations: [],
        };

        this.translate = this.translate.bind(this);
        this.addWord = this.addWord.bind(this);
        this.removeWord = this.removeWord.bind(this);
    }

    translate(){
        if (!this.props.term) {
            return;
        }
        Http.fetchTranslation(this.props.term).then((result) => {
                this.setState((prevState, props) => {
                        return {
                            translations: result,
                        }
                    }
                );
                this.renderWords();
            }
        );
    }

    addWord(word){
        let words = this.props.words;
        words.push(
            {
                lemma: word.translatedText,
                language: word.language
            }
        );
        this.props.updateWords(words);

        let translations = this.state.translations.filter((w) => w.translatedText !== word.translatedText);
        this.setState((prevState, props) => {
                return {
                    translations: translations,
                }
            }
        );
    }

    removeWord(word){
        let words = this.props.words;

        this.props.updateWords(words.filter((w) => w.lemma !== word.lemma));
    }

    renderWords() {
        let combinedWords = this.props.words.concat(this.state.translations);
        return (
            <ListGroup>
                {combinedWords.map((word, index) =>
                    <ListGroupItem key={index}>
                            {word.hasOwnProperty('translatedText') ?
                            word.translatedText : word.lemma
                            }
                        {word.hasOwnProperty('translatedText') ?
                            <Button size="lg" className="float-right" outline color="success" onClick={() => this.addWord(word)}>
                                +
                            </Button>
                            :
                            <Button size="lg" className="float-right" outline color="danger" onClick={() => this.removeWord(word)}>
                                -
                            </Button>
                        }
                    </ListGroupItem>
                )}
            </ListGroup>
        )
    }

    render (){
        return (
            <div className="words component">
                <div>
                    <Button outline block
                        onClick={this.translate}
                        color="info"
                    >Translate "{this.props.term}"</Button>
                </div>
                {this.renderWords()}
            </div>
        )
    }
}