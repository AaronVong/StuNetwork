function quickSort(arr, left, right, ascending = true, field = null) {
    let len = arr.length,
        pivot,
        partitionIndex;

    if (left < right) {
        pivot = right;
        partitionIndex = partition(arr, pivot, left, right, ascending, field);

        //sort left and right
        quickSort(arr, left, partitionIndex - 1, ascending, field);
        quickSort(arr, partitionIndex + 1, right, ascending, field);
    }
    return arr;
}

function partition(arr, pivot, left, right, ascending = true, field = null) {
    let pivotValue, partitionIndex;
    if (field) {
        (pivotValue = arr[pivot][field]), (partitionIndex = left);
    } else {
        (pivotValue = arr[pivot]), (partitionIndex = left);
    }

    for (let i = left; i < right; i++) {
        if (field) {
            if (ascending) {
                if (arr[i][field] < pivotValue) {
                    swap(arr, i, partitionIndex, field);
                    partitionIndex++;
                }
            } else {
                if (arr[i][field] > pivotValue) {
                    swap(arr, i, partitionIndex, field);
                    partitionIndex++;
                }
            }
        } else {
            if (ascending) {
                if (arr[i] < pivotValue) {
                    swap(arr, i, partitionIndex, field);
                    partitionIndex++;
                }
            } else {
                if (arr[i] > pivotValue) {
                    swap(arr, i, partitionIndex, field);
                    partitionIndex++;
                }
            }
        }
    }

    swap(arr, right, partitionIndex, field);
    return partitionIndex;
}

function swap(arr, i, j, field = null) {
    let temp;
    if (field) {
        temp = { ...arr[i] };
        arr[i] = { ...arr[j] };
        arr[j] = { ...temp };
    } else {
        temp = arr[i];
        arr[i] = arr[j];
        arr[j] = temp;
    }
}

export { quickSort };
