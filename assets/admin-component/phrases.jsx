export class Phrases extends React.Component {

    constructor() {
        super();
        this.state = {
            newPhrase: {
                content: '',
                language: 'fr',
                url_source: ''
            }
        };

        this.handleContentChange = this.handleContentChange.bind(this);
        this.handleLanguageChange = this.handleLanguageChange.bind(this);
        this.handleSourceChange = this.handleSourceChange.bind(this);
        this.renderPhrases = this.renderPhrases.bind(this);
        this.addPhrase = this.addPhrase.bind(this);
        this.removePhrase = this.removePhrase.bind(this);

    }

    handleContentChange(event){
        let np = this.state.newPhrase;
        np.content = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleLanguageChange(event){
        let np = this.state.newPhrase;
        np.language = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleSourceChange(event){
        let np = this.state.newPhrase;
        np.url_source = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    addPhrase(){
        let ph = this.props.phrases;
        ph.push(this.state.newPhrase);
        this.props.updatePhrases(ph)
    }

    removePhrase(phrase){
        console.log(phrase);
        let ph = this.props.phrases.filter((phr) => phr.content !== phrase.content);
        this.props.updatePhrases(ph)
    }

    renderPhrases(){
        return(
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
                <tr>
                    <th>
                        <input value={this.state.newPhrase.content} placeholder="content" onChange={this.handleContentChange}/>
                    </th>
                    <th>
                        <input value={this.state.newPhrase.url_source} placeholder="source url" onChange={this.handleSourceChange}/>
                    </th>
                    <th>
                        <input value={this.state.newPhrase.language} placeholder="language" onChange={this.handleLanguageChange}/>
                    </th>
                    <th>
                        <button onClick={this.addPhrase}>Add</button>
                    </th>
                </tr>
                </tbody>
            </table>
        )
    }
}