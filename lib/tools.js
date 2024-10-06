class Tools{
    static FormInputs(form) {
        const formElement = document.getElementById(form);
        if (!formElement) {
            throw new Error(`Form with id "${form}" not found`);
        }
        const formData = {};
        const elements = formElement.querySelectorAll('input, select, textarea');

        elements.forEach(element => {
            const key = element.name;
            const value = element.value;
            if (key) {
                if (element.type === 'radio') { 
                    if (element.checked) { formData[key] = element.value; } 
                    } else if (element.type === 'checkbox') { 
                        formData[key] = element.checked ? element.value : null; 
                    } else { 
                        formData[key] = element.value;
                    }
            }
        });
        return JSON.stringify(formData);
    }

    static ExecuteSql(sp, data) {
        return $.ajax({
            url: "../conn/mysql.php",
            type: "POST",
            data: { param1: sp, param2: data },
        }).then((response) => {
            try {
                // const response = JSON.parse(res);
                if (typeof response === 'object' && response !== null) {
                    return response;
                } else {
                    return response;
                }
            } catch (e) {
                console.error('Error parsing JSON:', e);
                return Promise.reject('Error parsing JSON: ' + e.message);
            }
        }).catch((xhr, status, error) => {
            console.error('AJAX request failed:', status, error);
            return Promise.reject({ xhr: xhr, status: status, error: error });
        });
    }

    static SqlTableAdapter(sp, data) {
        return $.ajax({
            url: "../conn/sqladapter.php",
            type: "POST",
            data: { param1: sp, param2: data },
        }).then((response) => {
            try {
                return response;
            } catch (e) {
                console.error('Error parsing JSON:', e);
                return Promise.reject('Error parsing JSON: ' + e.message);
            }
        }).catch((xhr, status, error) => {
            console.error('AJAX request failed:', status, error);
            return Promise.reject({ xhr: xhr, status: status, error: error });
        });
    }
    

    static SubmitRequest(spname, formId, button) {
        $('#' + button).click(function(){
            var jsonData = Tools.FormInputs(formId);
            console.log(jsonData)
            Tools.ExecuteSql(spname, jsonData).then(function(response) {
                if (response.code === 1) {
                    alert(response.data);
                    $('#' + formId)[0].reset();
                } else {
                    alert(response.data);
                }
            }).catch(function(error) { console.error('Error:', error); });
        });
    }

    static PopulateTable(tableId, data) {
        const table = document.getElementById(tableId);
        table.innerHTML = ""; // Clear existing table content
        if (data.length === 0) return; // Exit if no data
        // Extract keys (column headers) from the first object in the data array
        const keys = Object.keys(data[0]);
        // Create table header row
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        keys.forEach(key => {
            const th = document.createElement('th');
            th.textContent = key;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);
        // Create table body and rows for each data entry
        const tbody = document.createElement('tbody');
        data.forEach(row => {
            const dataRow = document.createElement('tr');
            keys.forEach(key => {
                const td = document.createElement('td');
                td.textContent = row[key] || ''; // Handle missing data
                dataRow.appendChild(td);
            });
            tbody.appendChild(dataRow);
        });
        table.appendChild(tbody);
    }    static PopulateTable(tableId, data) {
        const table = document.getElementById(tableId);
        table.innerHTML = ""; // Clear existing table content
    
        if (!Array.isArray(data) || data.length === 0 || !data[0]) {
            console.error("Invalid data provided.");
            return;
        }
    
        // Extract keys (column headers) from the first object in the data array
        const keys = Object.keys(data[0]);
    
        // Create table header row
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        keys.forEach(key => {
            const th = document.createElement('th');
            th.textContent = key;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);
    
        // Create table body and rows for each data entry
        const tbody = document.createElement('tbody');
        data.forEach(row => {
            const dataRow = document.createElement('tr');
            keys.forEach(key => {
                const td = document.createElement('td');
                td.textContent = row[key] !== undefined && row[key] !== null ? row[key] : ''; // Handle missing data
                dataRow.appendChild(td);
            });
            tbody.appendChild(dataRow);
        });
        table.appendChild(tbody);
    }
    

    static ExportTableToExcel(tableID, filename = '', activityTitle, owner) {
        // Get table element
        var table = document.getElementById(tableID);
        
        // Create a workbook and a worksheet
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet([]);
        
        // Add custom rows
        XLSX.utils.sheet_add_aoa(ws, [[`Activity Title: ${activityTitle}`]], { origin: -1 });
        XLSX.utils.sheet_add_aoa(ws, [[`Owner of Activity: ${owner}`]], { origin: -1 });
        
        // Convert the entire table including headers to sheet data and append after custom rows
        var tableData = XLSX.utils.table_to_sheet(table);
        
        // Add table data (including headers) to the worksheet
        XLSX.utils.sheet_add_json(ws, XLSX.utils.sheet_to_json(tableData, { header: 1 }), { skipHeader: true, origin: -1 });
        
        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
        
        // Generate Excel file
        var wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
        
        // Helper function to convert the output to a binary format
        function s2ab(s) {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }
        
        // Create a Blob and download the file
        var blob = new Blob([s2ab(wbout)], { type: "application/octet-stream" });
        var link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename ? filename + '.xlsx' : 'table.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    static addClassToColumnByHeader(tableId, headerName, className) {
        // Get the table
        let table = document.getElementById(tableId);
        // Get the header row (assuming the first row is the header)
        let headerRow = table.rows[0];
        // Find the index of the column with the matching header name
        let columnIndex = -1;
        for (let i = 0; i < headerRow.cells.length; i++) {
            if (headerRow.cells[i].innerText.trim().toLowerCase() === headerName.toLowerCase()) {
                columnIndex = i;
                break;
            }
        }  
        // If the column was found, add the class to all cells in that column
        if (columnIndex !== -1) {
            for (let i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[columnIndex].classList.add(className);
            }
        } else {
            console.error(`Column with header "${headerName}" not found.`);
        }
    }
    static createHiddenTableFromData(fullDataSet, tableId = 'tempExportTable') {
        // Check if the temporary table already exists and remove it if so
        var existingTable = $('#' + tableId);
        if (existingTable.length) {
            existingTable.remove(); // Clear the existing table
        }

        // Create a hidden table element with the provided or default tableId
        var tempTable = $('<table id="' + tableId + '" style="display:none;"></table>');
        $('body').append(tempTable);
    
        // Get the column names from the first object in the dataset
        var columns = Object.keys(fullDataSet[0] || {});
    
        // Create and append the table header row
        var headerRow = $('<tr></tr>');
        columns.forEach(function(column) {
            headerRow.append('<th>' + column + '</th>');
        });
        tempTable.append(headerRow);
    
        // Loop through the dataset and create table rows
        fullDataSet.forEach(function(row) {
            var dataRow = $('<tr></tr>');
            columns.forEach(function(column) {
                dataRow.append('<td>' + row[column] + '</td>');
            });
            tempTable.append(dataRow);
        });
    
        return tempTable;
    }

    static setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
    static getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}