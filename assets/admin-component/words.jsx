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
        this.renderNewWords = this.renderNewWords.bind(this);
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
                    this.renderNewWords()
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

    renderNewWords(){
        this.setState((prevState, props) => {
            return {
                translationList: (
                    <tbody>
                    {this.state.translations.map((word, index) =>
                        <tr key={index}>
                            <td>
                                {word.translatedText}
                            </td>
                            <td>
                                {word.language}
                            </td>
                            <td>
                                {word.source}
                            </td>
                            <td>
                                <button onClick={() => this.addWord(word)}>
                                    add
                                </button>
                            </td>
                        </tr>
                    )}
                    </tbody>
                ),
            }
        });
    }

    renderWords() {
        return (
            <tbody>
            {this.props.words.map((word, index) =>
                <tr key={index}>
                    <td>
                        {word.lemma}
                    </td>
                    <td>
                        {word.language}
                    </td>
                    <td>

                    </td>
                    <td>
                        <button onClick={() => this.removeWord(word)}>
                            remove
                        </button>
                    </td>
                </tr>
            )}
            </tbody>
        )
    }

    render (){
        return (
            <div className="words">
                <div className="form-inline">
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
                </div>
                <table className="table table-bordered">
                    <thead className="thead-light">
                        <tr>
                            <th>Lemma</th>
                            <th>Language</th>
                            <th>Source</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <thead className="thead-light">
                    <tr>
                        <th colSpan={4}>New Words</th>
                    </tr>
                    </thead>
                    {this.state.translationList ? this.state.translationList : null}
                    <thead className="thead-light">
                        <tr>
                            <th colSpan={4}>Existing Words</th>
                        </tr>
                    </thead>
                    {this.renderWords()}
                </table>
            </div>
        )
    }
}