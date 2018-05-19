export class MainWord extends React.Component {

    constructor() {
        super();
        this.state = {
            newInflection: ''
        };

        this.handleLemmaChange = this.handleLemmaChange.bind(this);
        this.handleGenderChange = this.handleGenderChange.bind(this);
        this.addInflection = this.addInflection.bind(this);
        this.removeInflection = this.removeInflection.bind(this);
        this.handleNewInflectionChange = this.handleNewInflectionChange.bind(this);

    }

    handleLemmaChange(event){
        let mw = this.props.mainWord;
        mw.lemma = event.target.value;
        this.props.updateMainWord(mw);
    }

    handleGenderChange(event){
        let mw = this.props.mainWord;
        mw.gender = event.target.value;
        this.props.updateMainWord(mw);
    }

    addInflection(){
        let mw = this.props.mainWord;
        mw.inflections.push({
            inflection : this.state.newInflection
        });
        this.props.updateMainWord(mw);
    }

    removeInflection(inflection){
        let mw = this.props.mainWord;
        mw.inflections = mw.inflections.filter((frm) => frm.inflection !== inflection.inflection);
        this.props.updateMainWord(mw);
    }

    handleNewInflectionChange(event){
        this.setState({
            newInflection: event.target.value
        })
    }

    render (){
        return (
            <div className="mainWord">
                <h1>{this.props.mainWord.lemma}</h1>
                <input
                    className="form-control"
                    value={this.props.mainWord.lemma}
                    onChange={this.handleLemmaChange}
                    type="text"
                    placeholder="enter word"
                />
                <ul>
                    {this.props.mainWord.inflections.map((inflection, key) =>
                        <li key={key}>
                            {inflection.inflection}
                            <button onClick={() => this.removeInflection(inflection)}>Remove</button>
                        </li>
                    )}
                    <li>
                        <input value={this.state.newInflection} onChange={this.handleNewInflectionChange}/>
                        <button onClick={this.addInflection}>add inflection</button>
                    </li>
                </ul>
                <select
                    value={this.props.mainWord.gender}
                    onChange={this.handleGenderChange}
                    className="form-control"
                >
                    <option value=""> / </option>
                    <option value="m"> male </option>
                    <option value="f"> female </option>
                </select>
            </div>
        )
    }

}