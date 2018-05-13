import {Http} from "./http";

export class Phrases extends React.Component {

    constructor() {
        super();
        this.state = {
            newPhrase: {
                content: '',
                language: 'fr',
                url_source: ''
            },
            newPhrases: []
        };

        this.handleContentChange = this.handleContentChange.bind(this);
        this.handleLanguageChange = this.handleLanguageChange.bind(this);
        this.handleSourceChange = this.handleSourceChange.bind(this);
        this.renderPhrases = this.renderPhrases.bind(this);
        this.addPhrase = this.addPhrase.bind(this);
        this.removePhrase = this.removePhrase.bind(this);
        this.fetchPhrases = this.fetchPhrases.bind(this);
        this.renderNewPhrases = this.renderNewPhrases.bind(this);
        this.addCustomPhrase = this.addCustomPhrase.bind(this);

    }

    handleContentChange(event) {
        let np = this.state.newPhrase;
        //strip html tags
        np.content = event.target.value.replace(/<(?:.|\n)*?>/gm, '');
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleLanguageChange(event) {
        let np = this.state.newPhrase;
        np.language = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleSourceChange(event) {
        let np = this.state.newPhrase;
        np.url_source = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    addPhrase(phrase) {
        let ph = this.props.phrases;
        ph.push(phrase);
        this.props.updatePhrases(ph)
    }

    addCustomPhrase(phrase){
        this.setState({
            newPhrase: {
                content: '',
                language: 'fr',
                url_source: ''
            }
        });
        this.addPhrase(phrase);
    }

    removePhrase(phrase) {
        let ph = this.props.phrases.filter((phr) => phr.content !== phrase.content);
        this.props.updatePhrases(ph)
    }

    fetchPhrases() {
        Http.fetchPhrases(this.props.term).then((result) => {

                this.setState((prevState, props) => {
                    return {
                        newPhrases: result,
                    }
                });
            }
        )
    }

    renderNewPhrases() {
        return (
            <React.Fragment>
                {this.state.newPhrases.map((phrase, key) => <tr key={key}>
                    <td>
                        {phrase.content}
                    </td>
                    <td>
                        {phrase.url_source}
                    </td>
                    <td>
                        {phrase.language}
                    </td>
                    <td>
                        <button onClick={() => this.addPhrase(phrase)}>add</button>
                    </td>
                </tr>)}
            </React.Fragment>
        )
    }

    renderPhrases() {
        return (
            <React.Fragment>
                {this.props.phrases.map((phrase, key) => <tr key={key}>
                    <td>
                        {phrase.content}
                    </td>
                    <td>
                        {phrase.url_source}
                    </td>
                    <td>
                        {phrase.language}
                    </td>
                    <td>
                        <button onClick={() => this.removePhrase(phrase)}>remove</button>
                    </td>
                </tr>)}
            </React.Fragment>
        )
    }

    render() {
        return (
            <table className="phrases table">
                <thead className="thead-light">
                <tr>
                    <th>Phrases</th>
                </tr>
                <tr>
                    <th>
                        Content
                    </th>
                    <th>
                        Source
                    </th>
                    <th>
                        Language
                    </th>
                    <th>
                        Operations
                    </th>
                </tr>
                </thead>
                <tbody>
                {this.renderPhrases()}
                {this.renderNewPhrases()}
                <tr>
                    <th>
                        <textarea value={this.state.newPhrase.content} placeholder="content"
                               onChange={this.handleContentChange}/>
                    </th>
                    <th>
                        <input value={this.state.newPhrase.url_source} placeholder="source url"
                               onChange={this.handleSourceChange}/>
                    </th>
                    <th>
                        <input value={this.state.newPhrase.language} placeholder="language"
                               onChange={this.handleLanguageChange}/>
                    </th>
                    <th>
                        <button onClick={()=> this.addCustomPhrase(this.state.newPhrase)}>Add</button>
                    </th>
                </tr>
                <tr>
                    <td>
                        <button onClick={this.fetchPhrases}>Fetch phrases for "{this.props.term}"</button>
                    </td>
                </tr>
                </tbody>
            </table>
        )
    }
}