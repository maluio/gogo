import {Http} from "./http";

export class Words extends React.Component {

    constructor(){
        super();
        this.state = {
            translations: [],
            translationList: ''
        };

        this.translate = this.translate.bind(this);
        this.renderNewWords = this.renderNewWords.bind(this);
        this.addWord = this.addWord.bind(this);
        this.removeWord = this.removeWord.bind(this);

    }

    translate(){
        Http.fetchTranslation(this.props.term).then((result) => {
                this.setState((prevState, props) => {
                        return {
                            translations: result,
                        }
                    }
                );
                this.renderNewWords();
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
                    <button
                        onClick={this.translate}
                        className="form-control"
                    >Translate "{this.props.term}"</button>
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
                        <th colSpan={4}>New translations</th>
                    </tr>
                    </thead>
                    {this.state.translationList ? this.state.translationList : null}
                    <thead className="thead-light">
                        <tr>
                            <th colSpan={4}>Existing translations</th>
                        </tr>
                    </thead>
                    {this.renderWords()}
                </table>
            </div>
        )
    }
}