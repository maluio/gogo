const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

// not used at the moment
class Word {
    constructor(lemma, language, wordClass=null, genus=null) {
        this.lemma = lemma;
        this.language = language;
        this.wordClass = wordClass;
        this.genus = genus;
    }
}

export class Words extends React.Component {

    constructor(){
        super();
        this.state = {
            term: '',
            translations: [],
            translationList: ''
        };

        this.translate = this.translate.bind(this);
        this.handleTranslateState = this.handleTranslateState.bind(this);
        this.renderTranslationList = this.renderTranslationList.bind(this);
        this.addWord = this.addWord.bind(this);
        this.removeWord = this.removeWord.bind(this);

    }

    handleTranslateState(event){
        let term = event.target.value;
        this.setState({term: term});
    }

    translate(){

        fetch(
            Routing.generate('translate', {term: this.state.term}),
            {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            translations: result,
                        }
                    });
                    this.renderTranslationList()
                },
                (error) => {
                }
            )
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
    }

    removeWord(word){
        let words = this.props.words;

        this.props.updateWords(words.filter((w) => w.lemma !== word.lemma));
    }

    renderTranslationList(){
        let items = this.state.translations.map((translation, index) =>
            <li
                key={index}
                onClick={() => this.addWord(translation)}
            >
                {translation.translatedText}
            </li>
        );
        this.setState((prevState, props) => {
            return {
                translationList: (
                    <div>
                        <h3>New Words</h3>
                        <ul>
                            {items}
                        </ul>
                    </div>
                ),
            }
        });
    }

    renderWords(){
        return(
            <ul>
                {this.props.words.map((word, index)=> <li key={index} onClick={()=>this.removeWord(word)}>
                    {word.lemma} ({word.language})
                </li>)}
            </ul>
        )
    }

    render (){
        return (
            <div className="words">
                <input
                    className="form-control"
                    value={this.state.term}
                    onChange={this.handleTranslateState}
                    type="text"
                    placeholder="search word(s)"
                />
                <button
                    onClick={this.translate}
                    className="form-control"
                >Translate</button>
                {this.state.translationList}
                <h3>exsiting words</h3>
                {this.renderWords()}
            </div>
        )
    }
}