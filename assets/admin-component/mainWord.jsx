export class MainWord extends React.Component {

    constructor() {
        super();
        this.state = {
            //lemma: null
        };

        this.handleLemmaChange = this.handleLemmaChange.bind(this);
        this.handleGenderChange = this.handleGenderChange.bind(this);

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