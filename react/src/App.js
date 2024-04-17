import PdfCard from "./PdfCard";

function App() {
  const cards = {  maxWidth: "1200px", margin: "0 auto", display: "grid", gap: "1rem", padding : '20px', gridTemplateColumns: "repeat(auto-fit, minmax(250px, 1fr))"}
  return (
    <div>
      <h2 style={{textAlign:'center'}}>List of report</h2>
      <div style={cards}>
        <PdfCard />
        
      </div>
    </div>
  );
}

export default App;
