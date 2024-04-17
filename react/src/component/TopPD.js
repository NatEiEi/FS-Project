import React, { Fragment, useState, useEffect } from 'react';
import { Image, Text, View, Page, Document, StyleSheet  } from '@react-pdf/renderer';
import axios from 'axios';
import logo from './Logo.png';



const TopPD = ({ startDate, endDate }) => {
    const [data, setData] = useState([]);

    // Font.register({
    //     family: 'Kanit-Black',
    //     src: 'C:/xampp/htdocs/shop3/my-app/public/assets/fonts/Kanit-Black.ttf'

    // });
      

    useEffect(() => {
        const fetchData = async () => {
          try {
            const response = await axios.get(`http://localhost:5000/api/TopPD?startDate=${startDate}&endDate=${endDate}`);
            setData(response.data);
            
          } catch (error) {
            console.error('Error fetching data:', error);
          }
        };


     

        fetchData();
    }, [startDate, endDate]);

        // console.log(data);


      
            
        const reciept_data = {
            "items": data
                .filter(item => item)
                .map(item => ({
                    "ProductID": item.ProductID,
                    "ProductName": item.ProductName,
                    "TotalQty": item.TotalQty
                }))
        };
        

                            
   

    


        const styles = StyleSheet.create({
        page: {fontSize: 11,paddingTop: 20,paddingLeft: 40,paddingRight: 40,lineHeight: 1.5,flexDirection: 'column' },

        spaceBetween : {flex : 1,flexDirection: 'row',alignItems:'center',justifyContent:'space-between',color: "#3E3E3E" },
        
        titleContainer: {flexDirection: 'row',marginTop: 24},
        
        logo: { width: 90 },

        reportTitle: {  fontSize: 16,  textAlign: 'center' },

        addressTitle : {fontSize: 11,fontStyle: 'bold'}, 
        
        invoice : {fontWeight: 'bold',fontSize: 20},
        
        invoiceNumber : {fontSize: 11,fontWeight: 'bold'}, 
        
        address : { fontWeight: 400, fontSize: 10},
        
        theader : {marginTop : 20,fontSize : 8,fontStyle: 'bold',paddingTop: 4 ,paddingLeft: 7 ,flex:1,height:20,backgroundColor : '#DEDEDE',borderColor : 'whitesmoke',borderRightWidth:1,borderBottomWidth:1},

        theader2 : { flex:2, borderRightWidth:0, borderBottomWidth:1},

        tbody:{ fontSize : 8, paddingTop: 4 , paddingLeft: 7 , flex:1, borderColor : 'whitesmoke', borderRightWidth:1, borderBottomWidth:1},

        total:{ fontSize : 9, paddingTop: 4 , paddingLeft: 7 , flex:1, borderColor : 'whitesmoke', borderBottomWidth:1},

        tbody2:{ flex:2, borderRightWidth:1, }
        
    });

    const InvoiceTitle = () => (
        <View style={styles.titleContainer}>
            <View style={styles.spaceBetween}>
                <Image style={styles.logo} src={logo} />
            </View>
            <View style={{ flex: 1.2, justifyContent: 'center' }}>
                <Text  >Top Sale</Text>
            </View>
        </View>
    );

    const Address = () => (
        <View >
            <View >
            <View style={{ flex: 1, textAlign: 'center' }}>
            </View>
            <View style={{ textAlign: 'right', flex: 1 }}>
                   <Text > {startDate} to {endDate} </Text>
            </View>
            </View>
        </View>
    );

    // const UserAddress = () => (
    //     <View style={styles.titleContainer}>
    //         <View style={styles.spaceBetween}>
    //             <View style={{maxWidth : 200}}>
    //                 <Text style={styles.addressTitle}>Bill to </Text>
    //                 <Text style={styles.address}>
    //                     {reciept_data.address}
    //                 </Text>
    //             </View>
    //             <Text style={styles.addressTitle}>{reciept_data.date}</Text>
    //         </View>
    //     </View>
    // );

 
    const TableHead = () => (
        <View style={{ width:'100%', flexDirection :'row', marginTop:1}}>ฃ
            <View style={styles.theader}>
                <Text >No.</Text>   
            </View>
            <View style={styles.theader}>
                <Text >ProductID</Text>   
            </View>
            <View style={styles.theader}>
                <Text>ProductName</Text>   
            </View>
            <View style={styles.theader}>
                <Text>Qty</Text>   
            </View>
        </View>
    );
    
    const TableBody = () => (
        reciept_data.items.map((receipt, index) => (
            <Fragment key={receipt.id}>
                <View style={{ width:'100%', flexDirection :'row'}}>
                    <View style={styles.tbody}>
                        <Text>{index + 1}</Text> {/* เพิ่ม column number */}
                    </View>
                    <View style={styles.tbody}>
                        <Text>{receipt.ProductID}</Text>   
                    </View>
                    <View style={styles.tbody}>
                        <Text>{receipt.ProductName}</Text>   
                    </View>
                    <View style={styles.tbody}>
                        <Text>{receipt.TotalQty}</Text>   
                    </View>
                </View>
            </Fragment>
        ))
    );
    

    const TableTotal = () => (
        <View style={{ width:'100%', flexDirection :'row'}}>
            <View style={styles.total}>
                <Text></Text>   
            </View>
            <View style={styles.total}>
                <Text></Text>   
            </View>
            <View style={styles.total}>
                <Text></Text>   
            </View>
            <View style={styles.total}>
                <Text> </Text>   
            </View>
            <View style={styles.tbody}>
                <Text>TotalQty</Text>   
            </View>
            <View style={styles.tbody}>
                <Text>
                    {reciept_data.items.reduce((sum, item)=> sum + (item.TotalQty), 0)}
                </Text>  
            </View>

           
            
        </View>
    );

    return (
        <Document>
        <Page size="A4" style={styles.page}>
            <InvoiceTitle  />
            <Address/>
            {/* <UserAddress/> */}
            <TableHead/>
            <TableBody/>
            <TableTotal/>
        </Page>
    </Document>
          
    )
}

export default TopPD