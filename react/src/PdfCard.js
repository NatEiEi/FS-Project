import React, { useState } from 'react';
import { CgFileDocument } from 'react-icons/cg';
import { HiOutlineDownload, HiOutlinePrinter } from 'react-icons/hi';
import { BlobProvider, PDFDownloadLink } from '@react-pdf/renderer';
import Invoice from './component/Invoice';
import UserType from './component/UserType';
import TopPD from './component/TopPD';

import CancleOrder from './component/CancleOrder'; 
import PaymentType from './component/PaymentType';


    const PdfCard = ({ title ,thai}) => {
        const today = new Date();
        
        const defaultStartDate = new Date(today.getFullYear(), today.getMonth(), 1); 
        
        const defaultEndDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 2); 
        const [startDate, setStartDate] = useState(defaultStartDate.toISOString().split('T')[0]);    
        // const [endDate, setEndDate] = useState(new Date().toISOString().split('T')[0]);    
        
        const [endDate, setEndDate] = useState(defaultEndDate.toISOString().split('T')[0]);

        const [isConfirmed, setIsConfirmed] = useState(false); // เพิ่ม state เพื่อตรวจสอบว่าวันที่ได้รับการยืนยันแล้วหรือไม่

    

    const styles = {
        container: { width: '220px', borderRadius: '5px', padding: '15px 12px', display: 'flex', flexDirection: 'column', gap: '15px', boxShadow: "0 3px 10px rgb(0 0 0 / 0.2)" },
        flex: { width: '100%', display: 'flex', gap: '5px', alignItems: 'center' },
        bold: { fontSize: '13px', fontWeight: 600 },
        thin: { fontSize: '11px', color: '#6f6f6f', fontWeight: 500 },
        btn: { borderRadius: '3px', border: '1px solid gray', display: 'flex', alignItems: 'center', gap: '2px', padding: '3px', fontSize: '11px', color: '#4f4f4f', fontWeight: 600, cursor: 'pointer', userSelect: 'none' }
    }

    const handleConfirmDate = () => {
        setIsConfirmed(true); // ทำการยืนยันวันที่
    };


    return (
        <div>
        <label htmlFor="dateStart">Start Date:</label>
        <input type="date" name="dateStart" value={startDate} onChange={(e) => setStartDate(e.target.value)} />

        <label htmlFor="dateEnd">End Date:</label>
        <input type="date" name="dateEnd" value={endDate} onChange={(e) => setEndDate(e.target.value)} />

        {/* เพิ่มปุ่มสำหรับยืนยันวันที่ */}
        <button onClick={handleConfirmDate}>ยืนยันวันที่</button>

        {/* ถ้ามีการยืนยันวันที่แล้ว ให้แสดงปุ่มสำหรับดาวน์โหลด PDF */}
        {isConfirmed && (
            <div>
            <div style={styles.flex}>
                <CgFileDocument color='#90e0ef' size={20} />
                <span style={styles.bold}>Summry</span>
            </div>
            <div style={{ ...styles.flex, ...{  } }}>
                <PDFDownloadLink document={<Invoice startDate={startDate} endDate={endDate} />} fileName='invoice.pdf'>
                    <div style={styles.btn}>
                        <HiOutlineDownload size={14} />
                        <span>Download</span>
                    </div>
                </PDFDownloadLink>
    
                <BlobProvider document={<Invoice startDate={startDate} endDate={endDate} />}>
                    {({ url, blob }) => (
                        <a href={url} target="_blank" rel="noreferrer" style={styles.btn}>
                            <HiOutlinePrinter size={14} />
                            <span>Print</span>
                        </a>
                    )}
                </BlobProvider>
            </div>

            <div style={styles.flex}>
                <CgFileDocument color='#90e0ef' size={20} />
                <span style={styles.bold}>Top Sale</span>
            </div>
            <div style={{ ...styles.flex, ...{  } }}>
            <   PDFDownloadLink document={<TopPD startDate={startDate} endDate={endDate} />} fileName='invoice.pdf'>
                    <div style={styles.btn}>
                        <HiOutlineDownload size={14} />
                        <span>Download</span>
                    </div>
                </PDFDownloadLink>
    
                <BlobProvider document={<TopPD startDate={startDate} endDate={endDate} />}>
                    {({ url, blob }) => (
                        <a href={url} target="_blank" rel="noreferrer" style={styles.btn}>
                            <HiOutlinePrinter size={14} />
                            <span>Print</span>
                        </a>
                    )}
                </BlobProvider>
            </div>

            <div style={styles.flex}>
                <CgFileDocument color='#90e0ef' size={20} />
                <span style={styles.bold}>Cancel Order</span>
            </div>
            <div style={{ ...styles.flex, ...{  } }}>
                <PDFDownloadLink document={<CancleOrder startDate={startDate} endDate={endDate} />} fileName='invoice.pdf'>
                    <div style={styles.btn}>
                        <HiOutlineDownload size={14} />
                        <span>Download</span>
                    </div>
                </PDFDownloadLink>
    
                <BlobProvider document={<CancleOrder startDate={startDate} endDate={endDate} />}>
                    {({ url, blob }) => (
                        <a href={url} target="_blank" rel="noreferrer" style={styles.btn}>
                            <HiOutlinePrinter size={14} />
                            <span>Print</span>
                        </a>
                    )}
                </BlobProvider>
            </div>

            <div style={styles.flex}>
                <CgFileDocument color='#90e0ef' size={20} />
                <span style={styles.bold}>User Type</span>
            </div>
            <div style={{ ...styles.flex, ...{  } }}>
            <PDFDownloadLink document={<UserType startDate={startDate} endDate={endDate} />} fileName='invoice.pdf'>
                    <div style={styles.btn}>
                        <HiOutlineDownload size={14} />
                        <span>Download</span>
                    </div>
                </PDFDownloadLink>
    
                <BlobProvider document={<UserType startDate={startDate} endDate={endDate} />}>
                    {({ url, blob }) => (
                        <a href={url} target="_blank" rel="noreferrer" style={styles.btn}>
                            <HiOutlinePrinter size={14} />
                            <span>Print</span>
                        </a>
                    )}
                </BlobProvider>
            </div>

            <div style={styles.flex}>
                <CgFileDocument color='#90e0ef' size={20} />
                <span style={styles.bold}>Payment Type</span>
            </div>
            <div style={{ ...styles.flex, ...{  } }}>
            <PDFDownloadLink document={<PaymentType startDate={startDate} endDate={endDate} />} fileName='invoice.pdf'>
                    <div style={styles.btn}>
                        <HiOutlineDownload size={14} />
                        <span>Download</span>
                    </div>
                </PDFDownloadLink>
    
                <BlobProvider document={<PaymentType startDate={startDate} endDate={endDate} />}>
                    {({ url, blob }) => (
                        <a href={url} target="_blank" rel="noreferrer" style={styles.btn}>
                            <HiOutlinePrinter size={14} />
                            <span>Print</span>
                        </a>
                    )}
                </BlobProvider>
            </div>

        
         
        </div>
    )}
</div>

           




            
     
    )
}

export default PdfCard
